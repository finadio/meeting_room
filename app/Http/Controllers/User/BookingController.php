<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmation;
use App\Rules\NotInPast;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Facility;
use App\Models\User;
use App\Models\Booking;
use App\Services\WhatsAppAPIService;
use App\Http\Controllers\SendMessage;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $facilities = Facility::all();
        $sortBy = $request->input('sort_by', 'price_lowest');
        $bookmarkedIds = [];
        if (auth()->check()) {
            $bookmarkedIds = auth()->user()->bookmarkedFacilities->pluck('id')->toArray();
        }
        return view('user.booking.index', compact('facilities', 'sortBy', 'bookmarkedIds'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'price_lowest');

        $facilitiesQuery = Facility::where('name', 'like', "%$search%")
            ->orWhere('location', 'like', "%$search%");

        switch ($sortBy) {
            case 'price_highest':
                $facilitiesQuery->orderByDesc('price_per_hour');
                break;
            case 'price_lowest':
            default:
                $facilitiesQuery->orderBy('price_per_hour');
                break;
        }

        $facilities = $facilitiesQuery->get();

        return view('user.booking.index', compact('facilities', 'search', 'sortBy'));
    }

    public function bookmark(Facility $facility)
    {
        $user = auth()->user();

        if (!$user->bookmarkedFacilities->contains($facility)) {
            $user->bookmarkedFacilities()->attach($facility);

            return response()->json(['status' => 'success', 'message' => 'Fasilitas berhasil di-bookmark.']);
        }

        return response()->json(['status' => 'warning', 'message' => 'Fasilitas sudah di-bookmark.'], 200); 
    }

    public function bookmarks()
    {
        $bookmarkedFacilities = auth()->user()->bookmarkedFacilities;
        return view('user.booking.bookmarks', compact('bookmarkedFacilities'));
    }

    public function unbookmark($facilityId)
    {
        $user = Auth::user();

        $facility = Facility::find($facilityId);

        if (!$facility || !$user) {
          
            return response()->json(['status' => 'error', 'message' => 'Permintaan tidak valid.'], 400); 
        }

        $user->bookmarkedFacilities()->detach($facility);

     
        return response()->json(['status' => 'success', 'message' => 'Fasilitas berhasil di-unbookmark.']);
    }


    public function show($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);

        $ratingsAndReviews = Booking::where('facility_id', $facility->id)
            ->whereNotNull('ratings')
            ->whereNotNull('reviews')
            ->with('user')
            ->get();

        return view('user.booking.show', compact('facility', 'ratingsAndReviews'));
    }

    public function showBookings()
    {
        $user = auth()->user();
        $bookings = $user->bookings()->latest()->paginate(5);

        return view('user.booking.my-booking', compact('bookings'));
    }

    public function confirm(Request $request, $facilityId)
    {
        try {
        
            // Ambil data fasilitas berdasarkan facility_id 
            $facility = Facility::find($facilityId);

            // Ambil nomor WhatsApp dari field contact_phone di tabel facility
            $no_wa = $facility->contact_phone;

            $token = env('ACCESS_TOKEN');
            $facility = Facility::findOrFail($facilityId);
           
            $validator = Validator::make($request->all(), [
                'date' => 'required', 'date', new NotInPast(),
                'time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:booking_time',
                'meeting_title' => 'string|max:255',
                'group_name' => 'string|max:255',

            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
           
             // Cek apakah sudah ada booking yang disetujui untuk ruang dan waktu yang sama
                
                $existingBooking = Booking::where('facility_id', $facility->id)
                ->where('booking_date', $request->date)
                ->whereIn('status', ['Disetujui', 'Menunggu Konfirmasi'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('booking_time', [$request->time, $request->end_time])
                        ->orWhereBetween('booking_end', [$request->time, $request->end_time])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('booking_time', '<=', $request->time)
                                ->where('booking_end', '>=', $request->end_time);
                        });
                })
                ->exists();

                if ($existingBooking) {
                    return redirect()->back()->with('error', 'Ruang ini sudah dipesan untuk tanggal dan waktu tersebut.');
                }
            $totalPrice = 0;
        //     Jika tidak ada booking yang bentrok, buat booking baru
            $user = auth()->user();
            $booking = new Booking();
            $booking->user_id = auth()->user()->id;
            $booking->facility_id = $facilityId;
            $booking->amount = $totalPrice;
            $booking->user_name = $user->name;
            $booking->email = $user->email;
            $booking->contact_number = $user->contact_number;
            $booking->booking_date = $request->input('date');
            $booking->booking_time = $request->input('time');
            $booking->booking_end = $request->input('end_time');
            $booking->meeting_title = $request->input('meeting_title');
            $booking->group_name = $request->input('group_name');
            $booking->save();

            // Kirim notifikasi WhatsApp ke Admin
             
             $message = "Booking baru dari " . auth()->user()->name . " untuk ruang " . $booking->facility->name . " pada tanggal " . $booking->booking_date . " dari " . $booking->booking_time . " sampai " . $booking->booking_end . ".";
            
            $sendMessage = new SendMessage();
            $response = $sendMessage->sendMessageAttemp($no_wa, $message, [['key' => 1, 'value' => 'app', 'value_text' => 'Booking Meeting room'], 
            ['key' => 2, 'value' => 'nama', 'value_text' => auth()->user()->name . " untuk ruang " . $booking->facility->name . " pada tanggal " . $booking->booking_date . " dari jam " . $booking->booking_time . " WIB sampai " . $booking->booking_end . " WIB ."]]);
            
            Log::info('Respon dari API WhatsApp: ' . json_encode($response));

            // Set the booking ID in the session
            $request->session()->put('booking.id', $booking->id);

            // Set other booking details in the session
            $request->session()->put('booking.facility_id', $facilityId);
            $request->session()->put('booking.date', $request->input('date'));
            $request->session()->put('booking.time', $request->input('time'));
            $request->session()->put('booking.end', $request->input('end_time'));

            // return view('user.booking.confirmation', compact('facility', 'booking'));
            $user = auth()->user();
            $bookings = $user->bookings()->latest()->paginate(5);

            return view('user.booking.my-booking', compact('bookings'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function generateReceipt()
    {
        $facilityId = session('booking.facility_id');

        $facility = Facility::find($facilityId);

        if (!$facility) {
            \Log::error("Facility not found for facility_id: $facilityId");
            return redirect()->route('error')->with('message', 'Facility not found.');
        }

        $bookingDate = session('booking.date');
        $bookingTime = session('booking.time');
        $bookingHour = session('booking.hours');
        $bookingAmount = session('booking.amount');


        $userId = auth()->id();
        $user = User::find($userId);

        $receiptContent = view('user.booking.receipt', compact('facility', 'bookingDate', 'bookingTime', 'bookingAmount', 'bookingHour', 'user'))->render();

        $pdf = \PDF::loadHtml($receiptContent);

        $filename = 'receipt_' . time() . '_' . Str::random(8) . '.pdf';

        Storage::makeDirectory("public/receipts");

        $pdf->save(storage_path("app/public/receipts/$filename"));

        return Storage::download("public/receipts/$filename", $filename);
    }

    public function paymentSuccess(Request $request)
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
            $paymentMethod = $request->input('paymentMethod');
            $booking->payment_method = $paymentMethod;

            $booking->save();

            // Send booking confirmation email
            $user = auth()->user();
            Mail::to($user->email)->send(new BookingConfirmation($booking));

            return view('user.booking.payment-success');
        } catch (\Exception $e) {
            return view('user.booking.payment-error', ['error' => $e->getMessage()]);
        }
    }

    public function storeReview(Request $request, Booking $booking)
    {
        try {
            $request->validate([
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'review' => ['nullable', 'string'],
            ]);

            // Check if the user has already reviewed this booking
            if ($booking->hasReviews()) {
                return redirect()->route('user.bookings', $booking->id)->with('error', 'You have already reviewed this booking.');
            }

            // Update ratings and reviews in the database
            $booking->ratings += $request->input('rating');
            $booking->reviews = $request->input('review');
            $booking->save();

            return redirect()->route('user.bookings', $booking->id)->with('success', 'Review added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('user.bookings', $booking->id)->with('error', 'Error adding review: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, Booking $booking)
    {
        $booking->update(['status' => 'Booking Cancelled']);

        return redirect()->back()->with('success', 'Booking has been cancelled successfully.');
    }

    public function Stripe_initiate(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('app.sk'));

        // Retrieve booking data from the request
        $facilityId = $request->session()->get('booking.facility_id');
        $bookingDate = $request->session()->get('booking.date');
        $bookingTime = $request->session()->get('booking.time');
        $bookingAmount = $request->session()->get('booking.amount');
        $bookingHour = $request->session()->get('booking.hours');
        $paymentMethod = 'Stripe';

        // Ensure all required data is present
        if (!$facilityId || !$bookingDate || !$bookingTime || !$bookingAmount || !$bookingHour) {
            throw new \Exception('Incomplete or missing booking information in session.');
        }

        // Retrieve booking data from the database using the facility ID, date, and time
        $booking = Booking::where('facility_id', $facilityId)
            ->where('booking_date', $bookingDate)
            ->where('booking_time', $bookingTime)
            ->first();

        // Check if the booking is not found
        if (!$booking) {
            throw new \Exception('Booking not found. Facility ID: ' . $facilityId . ', Date: ' . $bookingDate . ', Time: ' . $bookingTime);
        }

        // Retrieve facility details
        $facility = Facility::find($facilityId);

        $total = $bookingAmount * 100;

        // Create a Stripe Checkout session
        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'NPR',
                        'product_data' => [
                            'name' => $facility->name,
                            'images' => ['https://goalnepal.com/uploads/news/1627182357.jpg'],
                            'description' => $facility->description,
                        ],
                        'unit_amount' => $total,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('user.bookings.stripe.success'),
            'cancel_url' => route('user.bookings.stripe.cancel'),
        ]);

        $booking->payment_method = $paymentMethod;
        $booking->status = 'Payment Completed';
        $booking->save();

        // Send booking confirmation email
        $user = auth()->user();
        Mail::to($user->email)->send(new BookingConfirmation($booking));

        // Redirect to the Stripe Checkout session URL
        return redirect()->away($session->url);
    }


    public function Stripe_success()
    {
        return view('user.booking.payment-success');
    }

    public function Stripe_cancel(Request $request)
    {
        return redirect()->route('user.booking.index')->with('error', 'Payment process was canceled.');
    }

    public function checkIn($bookingId)
    {
        $booking = Booking::find($bookingId);

        // Cari booking sebelumnya yang berada pada ruangan dan tanggal yang sama, 
            // dan selesai sebelum waktu mulai booking ini
            $previousBooking = Booking::where('facility_id', $booking->facility_id)
            ->where('booking_date', $booking->booking_date)
            ->where('booking_end', '<=', $booking->booking_time) // Booking sebelumnya selesai sebelum booking saat ini dimulai
            ->orderBy('booking_end', 'desc') // Ambil booking yang paling akhir sebelum waktu mulai booking ini
            ->first();

        // Jika ada booking sebelumnya dan belum check-out, batalkan check-in
        if ($previousBooking && !$previousBooking->check_out) {
            return back()->with('error', 'Check-in tidak bisa dilakukan karena ruangan masih digunakan.');
        }

        $booking->check_in = now();
        $booking->is_checked_in = true;
        $booking->save();
        return redirect()->back()->with('success', 'Anda berhasil Check In.');
        return back()->with('status', 'Check-in berhasil');
    }

    public function checkOut($bookingId)
    {
        $booking = Booking::find($bookingId);
        $booking->check_out = now();
        $booking->is_checked_in = false;
        $booking->status = 'Selesai'; // Ubah status menjadi 'Selesai'
        $booking->save();
        return redirect()->back()->with('success', 'Anda berhasil Check Out dan status booking telah diubah menjadi Selesai.');
        // return back()->with('status', 'Check-out berhasil');
    }

    public function extendBooking($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Cek booking berikutnya dalam 30 menit ke depan
        $nextBooking = Booking::where('facility_id', $booking->facility_id)
            ->where('booking_date', $booking->booking_date)
            ->where('booking_time', '>=', $booking->booking_end)
            ->where('booking_time', '<=', \Carbon\Carbon::parse($booking->booking_end)->addMinutes(30))
            ->first();
    
        // Jika ada booking berikutnya dalam 30 menit, berikan pesan error dan hentikan proses extend
        if ($nextBooking) {
            // Simpan error message ke session untuk ditampilkan di view
            return back()->with('error', 'Tidak bisa diperpanjang karena sudah ada jadwal booking pada jam berikutnya, Mohon untuk segera menyelesaikan meeting Anda, Terima kasih.');
        }
    
        // Jika tidak ada booking dalam 30 menit, perpanjang booking
        $booking->booking_end = \Carbon\Carbon::parse($booking->booking_end)->addMinutes(30);
        $booking->save();
    
        return back()->with('success', 'Booking berhasil diperpanjang.');
    }
    
    public function myBookings()
    {
        $userBookings = Booking::where('user_id', auth()->id())
            ->with(['facility'])
            ->get()
            ->map(function ($booking) {
                // Hitung selisih waktu antara booking_end dan waktu saat ini
                $timeDifference = now()->diffInMinutes($booking->booking_end);

                // Cek apakah booking masih aktif dan waktu tersisa kurang dari 10 menit
                $isTimeToExtend = $timeDifference <= 10 && !$booking->next_booking_exists;

                // Menambahkan variabel isTimeToExtend ke dalam data booking
                $booking->isTimeToExtend = $isTimeToExtend;

                return $booking;
            });

        return view('user.my-bookings', compact('userBookings'));
    }

}
