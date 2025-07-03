<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // Pastikan ini diimpor
use App\Models\Booking; // Pastikan ini diimpor
use Carbon\Carbon; // Pastikan ini diimpor

class CalendarController extends Controller
{
    public function index(Request $request) // <-- PASTIKAN ada (Request $request) di sini
    {
        $user = auth()->user();
        
        // Ambil query pencarian dari request
        $searchQuery = $request->input('search'); // <-- PASTIKAN baris ini ada

        // Mulai query untuk bookings dengan eager loading user dan facility
        // Pastikan relasi 'facility' ada di model Booking
        $bookingsQuery = Booking::with(['user', 'facility']);

        // Terapkan filter pencarian jika ada searchQuery
        if ($searchQuery) { // <-- PASTIKAN blok if ini ada
            $bookingsQuery->where(function ($query) use ($searchQuery) {
                // Cari berdasarkan nama user, nama fasilitas, judul meeting (jika ada), atau status booking
                $query->whereHas('user', function ($q) use ($searchQuery) {
                    $q->where('name', 'like', '%' . $searchQuery . '%');
                })
                ->orWhereHas('facility', function ($q) use ($searchQuery) {
                    $q->where('name', 'like', '%' . $searchQuery . '%');
                })
                // Jika ada kolom 'meeting_title' di tabel bookings
                ->orWhere('meeting_title', 'like', '%' . $searchQuery . '%')
                ->orWhere('status', 'like', '%' . $searchQuery . '%');
            });
        }

        // Dapatkan hasil bookings yang sudah difilter
        // Variabel $bookings didefinisikan di sini
        $bookings = $bookingsQuery->get(); // <-- PASTIKAN baris ini ada, dan ini adalah $bookings dari query

        // Siapkan data untuk kalender dan list tabel
        // VARIABEL $bookedDates DIDEFINISIKAN DI SINI
        $bookedDates = $bookings->map(function ($booking) { // <-- PASTIKAN BARIS INI ADA, INI YANG MENDIFINISIKAN $bookedDates
            $bookingDate = Carbon::parse($booking->booking_date)->format('Y-m-d');
            $bookingTime = Carbon::parse($booking->booking_time)->format('H:i:s');
            $bookingHours = $booking->hours ?? 1; // Default 1 jam jika tidak ada
            $facilityName = $booking->facility->name;
            $userName = $booking->user->name; // Pastikan relasi user ada

            $title = "{$facilityName} - " . Carbon::parse($booking->booking_time)->format('h:i A');

            // Hitung waktu berakhir untuk FullCalendar
            $startDateTime = Carbon::parse($bookingDate . ' ' . $bookingTime);
            $endDateTime = $startDateTime->copy()->addHours($bookingHours);

            return [
                'title' => $title,
                'start' => $startDateTime->toIso8601String(), // Format ISO8601 untuk FullCalendar
                'end' => $endDateTime->toIso8601String(),     // Format ISO8601 untuk FullCalendar
                'bookingDate' => $bookingDate,
                'bookingTime' => $bookingTime,
                'bookingHours' => $bookingHours,
                'facilityName' => $facilityName,
                'userName' => $userName,
                'bookingStatus' => $booking->status,
                'bookingAmount' => $booking->amount ?? 0, // Default 0 jika tidak ada
                'bookingPaymentMethod' => $booking->payment_method ?? 'N/A', // Default N/A jika tidak ada
                // Class name untuk styling event di kalender (misal: warna berdasarkan status)
                'className' => 'fc-event-' . strtolower(str_replace(' ', '-', $booking->status)),
                'borderColor' => 'red', // Atribut border default untuk event
            ];
        }); 
        
        // Teruskan variabel $user dan $bookedDates ke tampilan
        // PASTIKAN $bookedDates DISERTAKAN DI compact()
        return view('user.calendar.index', compact('user', 'bookedDates')); // <-- PASTIKAN $bookedDates ada di sini
    }
}