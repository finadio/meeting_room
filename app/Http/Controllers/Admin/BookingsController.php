<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppAPIService; // <-- UBAH DARI SendMessage KE WhatsAppAPIService

class BookingsController extends Controller
{
    protected $sendMessage; // Properti ini akan menampung instance WhatsAppAPIService

    // Perbaiki type hint di constructor
    public function __construct(WhatsAppAPIService $sendMessage) // <-- UBAH type hint DI SINI
    {
        $this->sendMessage = $sendMessage;
    }

    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $statusFilter = $request->input('status');
        $facilityFilter = $request->input('facility_id');
        $dateFilter = $request->input('date');

        $bookings = Booking::with('user', 'facility')
            ->when($searchQuery, function ($query, $searchQuery) {
                $query->where(function ($q) use ($searchQuery) {
                    // Pencarian di kolom-kolom teks
                    $q->where('user_name', 'like', '%' . $searchQuery . '%')
                      ->orWhere('email', 'like', '%' . $searchQuery . '%')
                      ->orWhere('contact_number', 'like', '%' . $searchQuery . '%')
                      ->orWhere('status', 'like', '%' . $searchQuery . '%')
                      ->orWhere('meeting_title', 'like', '%' . $searchQuery . '%')
                      ->orWhere('group_name', 'like', '%' . $searchQuery . '%')
                      ->orWhereHas('facility', function ($qF) use ($searchQuery) {
                          $qF->where('name', 'like', '%' . $searchQuery . '%');
                      });

                    // Coba mencari berdasarkan nama bulan (misal: "January", "February", dll.)
                    try {
                        $monthNumber = null;
                        $lowerSearchQuery = strtolower($searchQuery);
                        $monthNames = [
                            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
                            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
                            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
                            'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
                            'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
                            'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
                        ];

                        if (isset($monthNames[$lowerSearchQuery])) {
                            $monthNumber = $monthNames[$lowerSearchQuery];
                            $q->orWhereMonth('booking_date', $monthNumber);
                        }
                    } catch (\Exception $e) {
                        Log::error("Error parsing month for search: " . $e->getMessage());
                    }

                    // Pencarian berdasarkan tanggal spesifik jika search query adalah tanggal (YYYY-MM-DD)
                    if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $searchQuery)) {
                        try {
                            $date = Carbon::parse($searchQuery)->format('Y-m-d');
                            $q->orWhereDate('booking_date', $date);
                        } catch (\Exception $e) {
                            // Tangani jika format tanggal tidak valid
                        }
                    }
                });
            })
            ->when($statusFilter, function ($query, $statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->when($facilityFilter, function ($query, $facilityFilter) {
                $query->where('facility_id', $facilityFilter);
            })
            ->when($dateFilter, function ($query, $dateFilter) {
                if ($dateFilter === 'today') {
                    $query->whereDate('booking_date', Carbon::today());
                } elseif ($dateFilter === 'this_week') {
                    $query->whereBetween('booking_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($dateFilter === 'this_month') {
                    $query->whereMonth('booking_date', Carbon::now()->month);
                } elseif ($dateFilter === 'this_year') {
                    $query->whereYear('booking_date', Carbon::now()->year);
                } else {
                    // Jika dateFilter adalah tanggal spesifik (YYYY-MM-DD)
                    try {
                        $date = Carbon::parse($dateFilter)->format('Y-m-d');
                        $query->whereDate('booking_date', $date);
                    } catch (\Exception $e) {
                        // Tangani jika format tanggal tidak valid
                    }
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $facilities = Facility::all();

        return view('admin.bookings.index', compact('bookings', 'facilities', 'searchQuery', 'statusFilter', 'facilityFilter', 'dateFilter'));
    }

    public function show(Booking $booking)
    {
        $bookingTime = Carbon::parse($booking->booking_time);
        $bookingEnd = Carbon::parse($booking->booking_end);
        $duration = $bookingTime->diff($bookingEnd);

        return view('admin.bookings.show', compact('booking', 'duration'));
    }

    public function adminkonfirm(Request $request)
    {
        try {
            $facilityId = session('booking.facility_id');
            $bookingDate = session('booking.date');
            $bookingTime = session('booking.time');
            $bookingHour = session('booking.hours');
            $bookingAmount = session('booking.amount');

            if (!$facilityId || !$bookingDate || !$bookingTime || !$bookingHour || !$bookingAmount) {
                throw new \Exception('Incomplete or missing booking information in session.');
            }

            $booking = Booking::where('facility_id', $facilityId)
                ->where('booking_date', $bookingDate)
                ->where('booking_time', $bookingTime)
                ->first();

            if (!$booking) {
                throw new \Exception('Booking not found. Facility ID: ' . $facilityId . ', Date: ' . $bookingDate . ', Time: ' . $bookingTime);
            }

            $booking->status = 'Payment Completed';
            $booking->save();

            $user = auth()->user();
            Mail::to($user->email)->send(new \App\Mail\BookingConfirmation($booking));

            return view('user.booking.payment-success');
        } catch (\Exception $e) {
            return view('user.booking.payment-error', ['error' => $e->getMessage()]);
        }
    }

    public function approve(Request $request, $bookingId)
    {
        $token = env('ACCESS_TOKEN');
        $booking = Booking::find($bookingId);

        $user = $booking->user;
        $no_wa = $user->contact_number;

        $booking->status = 'Disetujui';
        $booking->save();

        $message = "Booking baru dari " . auth()->user()->name . " untuk ruang " . $booking->facility->name . " pada tanggal " . $booking->booking_date . " dari " . $booking->booking_time . " sampai " . $booking->booking_end . ".";
        $response = $this->sendMessage->sendMessageAttemp($no_wa, $message, [['key' => 1, 'value' => 'app', 'value_text' => 'Booking Meeting room'],
        ['key' => 2, 'value' => 'nama', 'value_text' => "Anda " . " untuk ruang " . $booking->facility->name . " pada tanggal " . $booking->booking_date . " dari jam " . $booking->booking_time . " WIB sampai " . $booking->booking_end . " WIB  telah disetujui."]]);

        Log::info('Respon dari API WhatsApp: ' . json_encode($response));

        return redirect()->back()->with('success', 'Booking telah disetujui.');
    }

    public function checkBookingStatus()
    {
        $bookings = Booking::where('status', 'Disetujui')
            ->where('is_checked_in', 1)
            ->where('booking_date', '<', now())
            ->get();

        foreach ($bookings as $booking) {
            $booking->status = 'Extended';
            $booking->save();
        }

        Log::info("Booking yang telah selesai berhasil diperbarui.");
    }

    public function create()
    {
        $facilities = Facility::all();
        return view('admin.bookings.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'booking_end' => 'required|date_format:H:i|after:booking_time',
            'meeting_title' => 'required|string|max:255',
            'group_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $facilityId = $request->facility_id;
        $bookingDate = $request->date;
        $bookingStartTime = Carbon::parse($request->booking_time);
        $bookingEndTime = Carbon::parse($request->booking_end);

        $conflictingBooking = Booking::where('facility_id', $facilityId)
            ->where('booking_date', $bookingDate)
            ->where(function ($query) use ($bookingStartTime, $bookingEndTime) {
                $query->whereBetween('booking_time', [$bookingStartTime, $bookingEndTime])
                    ->orWhereBetween('booking_end', [$bookingStartTime, $bookingEndTime])
                    ->orWhere(function ($query) use ($bookingStartTime, $bookingEndTime) {
                        $query->where('booking_time', '<=', $bookingStartTime)
                            ->where('booking_end', '>=', $bookingEndTime);
                    });
            })
            ->exists();

        if ($conflictingBooking) {
            return redirect()->back()->with('error', 'Tidak bisa membuat booking karena waktu yang dipilih sudah dibooking.');
        }

        Booking::create([
            'facility_id' => $facilityId,
            'booking_date' => $bookingDate,
            'booking_time' => $request->booking_time,
            'booking_end' => $request->booking_end,
            'meeting_title' => $request->meeting_title,
            'group_name' => $request->group_name,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'contact_number' => $user->contact_number,
            'status' => 'Disetujui',
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dibuat oleh Admin.');
    }

    public function checkIn($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->check_in = now();
        $booking->is_checked_in = 1;
        $booking->save();

        return redirect()->back()->with('success', 'Check In berhasil.');
    }

    public function checkOut($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->check_out = now();
        $booking->status = 'Selesai';
        $booking->save();

        return redirect()->back()->with('success', 'Check Out berhasil.');
    }

    public function sendWhatsApp($bookingId)
    {
        try {
            Log::info('sendWhatsApp function called with booking ID: ' . $bookingId);

            $booking = Booking::findOrFail($bookingId);
            Log::info('Booking retrieved: ' . json_encode($booking));

            $no_wa = $booking->user->contact_number;
            $name = $booking->user->name;

            $currentTime = now();
            $bookingDate = Carbon::parse($booking->booking_date)->format('Y-m-d');
            $bookingEndTime = Carbon::parse($bookingDate . ' ' . $booking->booking_end);

            Log::info('Current Time: ' . $currentTime);
            Log::info('Booking End Time: ' . $bookingEndTime);

            if ($currentTime->greaterThan($bookingEndTime)) {
                Log::info('Waktu telah melewati booking_end');
                $message = [
                    ['key' => 1, 'value' => 'app', 'value_text' => 'Booking Meeting room'],
                    ['key' => 2, 'value' => 'info', 'value_text' => 'Anda telah melewati batas pemakaian, silahkan segera Logout.']
                ];
            } elseif ($bookingEndTime->diffInMinutes($currentTime) <= 10) {
                Log::info('Waktu tinggal 10 menit lagi');
                $message = [
                    ['key' => 1, 'value' => 'app', 'value_text' => 'Booking Meeting room'],
                    ['key' => 2, 'value' => 'nama', 'value_text' => 'Booking Anda untuk ruang ' . $booking->facility->name . ', akan berakhir kurang dari 10 menit lagi.']
                ];
            } else {
                Log::info('Tidak ada kondisi yang terpenuhi untuk mengirim WA');
                return redirect()->route('admin.bookings.index')->with('error', 'Tidak ada pesan yang dikirim karena kondisi tidak terpenuhi.');
            }

            Log::info('Final Message: ' . json_encode($message));

            $response = $this->sendMessage->sendMessageAttemp($no_wa, $name, $message);

            $responseData = json_decode($response, true);
            Log::info('WhatsApp API Response: ' . $response);

            if (isset($responseData['status']) && $responseData['status'] === 'success') {
                $booking->wa_sent = true;
                $booking->save();
                return redirect()->route('admin.bookings.index')->with('success', 'Pesan WhatsApp berhasil dikirim.');
            } else {
                return redirect()->route('admin.bookings.index')->with('error', 'Gagal mengirim pesan WhatsApp: ' . ($responseData['error']['messages'][0] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('Error in sendWhatsApp: ' . $e->getMessage());
            return redirect()->route('admin.bookings.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}