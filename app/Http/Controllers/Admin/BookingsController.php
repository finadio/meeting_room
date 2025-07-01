<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Controllers\SendMessage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Facility;
use Illuminate\Support\Facades\Auth;

class BookingsController extends Controller
{
    public function index()
    {
        // $bookings = Booking::with(['user', 'facility'])->latest()->paginate(5);
        $bookings = Booking::with(['user', 'facility'])
                        ->orderByDesc('booking_date') // Mengurutkan berdasarkan tanggal
                        ->orderBy('booking_time') // Mengurutkan berdasarkan waktu
                        ->paginate(5);
        $unreadNotificationCount = Notification::where('is_read', false)->count();
        return view('admin.bookings.index', compact('bookings', 'unreadNotificationCount'));
    }

    public function show(Booking $booking)
    {
        // $booking = Booking::find($id);
        $bookingTime = Carbon::parse($booking->booking_time);
        $bookingEnd = Carbon::parse($booking->booking_end);
        $duration = $bookingTime->diff($bookingEnd);

        return view('admin.bookings.show', compact('booking', 'duration'));
    }

    public function adminkonfirm(Request $request)
    {
        try {
            // Retrieve data from the session
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

            // Check if the booking is not found
            if (!$booking) {
                throw new \Exception('Booking not found. Facility ID: ' . $facilityId . ', Date: ' . $bookingDate . ', Time: ' . $bookingTime);
            }

            // Update the booking status to "completed"
            $booking->status = 'Payment Completed';
            // $paymentMethod = $request->input('paymentMethod');
            // $booking->payment_method = $paymentMethod;

            $booking->save();

            // Send booking confirmation email
            $user = auth()->user();
            Mail::to($user->email)->send(new BookingConfirmation($booking));

            return view('user.booking.payment-success');
        } catch (\Exception $e) {
            return view('user.booking.payment-error', ['error' => $e->getMessage()]);
        }
    }
    
    public function approve(Request $request, $bookingId)
    {
        
        $token = env('ACCESS_TOKEN');
        $booking = Booking::find($bookingId);
        
        // Ambil informasi user yang melakukan booking
        $user = $booking->user;
        $no_wa = $user->contact_number;
        
        // Update status booking menjadi disetujui
        $booking->status = 'Disetujui'; // Ganti dengan status sesuai kebutuhan
        $booking->save();

        // Kirim notifikasi WhatsApp ke Admin
             
        $message = "Booking baru dari " . auth()->user()->name . " untuk ruang " . $booking->facility->name . " pada tanggal " . $booking->booking_date . " dari " . $booking->booking_time . " sampai " . $booking->booking_end . ".";
        $sendMessage = new SendMessage();
        
        $response = $sendMessage->sendMessageAttemp($no_wa, $message, [['key' => 1, 'value' => 'app', 'value_text' => 'Booking Meeting room'], 
        ['key' => 2, 'value' => 'nama', 'value_text' => "Anda " . " untuk ruang " . $booking->facility->name . " pada tanggal " . $booking->booking_date . " dari jam " . $booking->booking_time . " WIB sampai " . $booking->booking_end . " WIB  telah disetujui."]]);
        
        Log::info('Respon dari API WhatsApp: ' . json_encode($response));
        
        return redirect()->back()->with('success', 'Booking telah disetujui.');
    }

    public function checkBookingStatus()
    {
        // Cari semua booking yang statusnya "Disetujui" dan booking_end telah lewat waktu sekarang
        $bookings = Booking::where('status', 'Disetujui')
            ->where('is_checked_in', 1) // Belum check-out
            ->where('booking_date', '<', now())
            ->get();

        // Update status menjadi "Selesai"
        foreach ($bookings as $booking) {
            $booking->status = 'Extended';
            $booking->save();
        }

        \Log::info("Booking yang telah selesai berhasil diperbarui.");
    }

    public function create()
    {
        $facilities = Facility::all(); // Ambil daftar fasilitas dari database
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

        // Ambil informasi pengguna yang sedang login
        $user = Auth::user();
        
        // Ambil input waktu booking baru
        $facilityId = $request->facility_id;
        $bookingDate = $request->date;
        $bookingStartTime = Carbon::parse($request->booking_time);
        $bookingEndTime = Carbon::parse($request->booking_end);

        // Cek apakah ada booking lain yang tumpang tindih di ruangan yang sama dan hari yang sama
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

        // Jika ada booking yang tumpang tindih, kembalikan pesan error
        if ($conflictingBooking) {
            return redirect()->back()->with('error', 'Tidak bisa membuat booking karena waktu yang dipilih sudah dibooking.');
        }

        // Jika tidak ada konflik, simpan booking baru
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
            'status' => 'Disetujui', // Langsung disetujui oleh admin
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

    protected $sendMessage;

    public function __construct(SendMessage $sendMessage)
    {
        $this->sendMessage = $sendMessage; // Menyuntikkan dependency SendMessage
    }

    public function sendWhatsApp($bookingId)
    {
        try {
            \Log::info('sendWhatsApp function called with booking ID: ' . $bookingId);

            $booking = Booking::findOrFail($bookingId);
            \Log::info('Booking retrieved: ' . json_encode($booking));

            $no_wa = $booking->user->contact_number;
            $name = $booking->user->name;

            $currentTime = now();
            $bookingDate = \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d');
            $bookingEndTime = \Carbon\Carbon::parse($bookingDate . ' ' . $booking->booking_end);

            \Log::info('Current Time: ' . $currentTime);
            \Log::info('Booking End Time: ' . $bookingEndTime);

            // Tentukan pesan berdasarkan kondisi
            if ($currentTime->greaterThan($bookingEndTime)) {
                \Log::info('Waktu telah melewati booking_end');
                $message = [
                    ['key' => 1, 'value' => 'app', 'value_text' => 'Booking Meeting room'],
                    ['key' => 2, 'value' => 'info', 'value_text' => 'Anda telah melewati batas pemakaian, silahkan segera Logout.']
                ];
            } elseif ($bookingEndTime->diffInMinutes($currentTime) <= 10) {
                \Log::info('Waktu tinggal 10 menit lagi');
                $message = [
                    ['key' => 1, 'value' => 'app', 'value_text' => 'Booking Meeting room'],
                    ['key' => 2, 'value' => 'nama', 'value_text' => 'Booking Anda untuk ruang ' . $booking->facility->name . ', akan berakhir kurang dari 10 menit lagi.']
                ];
            } else {
                \Log::info('Tidak ada kondisi yang terpenuhi untuk mengirim WA');
                return redirect()->route('admin.bookings.index')->with('error', 'Tidak ada pesan yang dikirim karena kondisi tidak terpenuhi.');
            }

            \Log::info('Final Message: ' . json_encode($message));

            // Kirim pesan WhatsApp
            $sendMessage = new SendMessage();
            $response = $sendMessage->sendMessageAttemp($no_wa, $name, $message);

            $responseData = json_decode($response, true);
            \Log::info('WhatsApp API Response: ' . $response);

            if ($responseData['status'] === 'success') {
                $booking->wa_sent = true;
                $booking->save();
                return redirect()->route('admin.bookings.index')->with('success', 'Pesan WhatsApp berhasil dikirim.');
            } else {
                return redirect()->route('admin.bookings.index')->with('error', 'Gagal mengirim pesan WhatsApp: ' . $responseData['error']['messages'][0]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in sendWhatsApp: ' . $e->getMessage());
            return redirect()->route('admin.bookings.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
