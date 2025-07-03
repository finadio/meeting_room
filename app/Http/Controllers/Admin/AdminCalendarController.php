<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking; // Pastikan ini diimpor
use Illuminate\Http\Request; // Pastikan ini diimpor
use Carbon\Carbon; // Pastikan ini diimpor
use App\Models\User; // Pastikan ini diimpor, karena relasi user digunakan
use App\Models\Facility; // Pastikan ini diimpor, karena relasi facility digunakan

class AdminCalendarController extends Controller
{
    public function index(Request $request) // <-- Metode ini menerima Request untuk input pencarian
    {
        $user = auth()->user(); // Mengambil data user yang sedang login

        // Mengambil query pencarian dari input 'search'
        $searchQuery = $request->input('search'); 

        // Memulai query untuk model Booking, dengan eager loading relasi 'user' dan 'facility'
        // Eager loading sangat penting untuk menghindari N+1 query problem
        $bookingsQuery = Booking::with(['user', 'facility']); 

        // Menerapkan filter pencarian jika ada 'searchQuery'
        if ($searchQuery) {
            $bookingsQuery->where(function ($query) use ($searchQuery) {
                // Mencari di kolom meeting_title, status, atau melalui relasi nama user/facility
                $query->where('meeting_title', 'like', '%' . $searchQuery . '%')
                      ->orWhere('status', 'like', '%' . $searchQuery . '%')
                      ->orWhereHas('user', function ($q) use ($searchQuery) {
                          $q->where('name', 'like', '%' . $searchQuery . '%');
                      })
                      ->orWhereHas('facility', function ($q) use ($searchQuery) {
                          $q->where('name', 'like', '%' . $searchQuery . '%');
                      });
            });
        }

        // Mendapatkan hasil bookings yang sudah difilter dari database
        // Gunakan get() untuk mengambil koleksi data, karena keduanya (kalender & list) akan memprosesnya
        $bookings = $bookingsQuery->get(); 

        // Memetakan data $bookings ke format yang dibutuhkan oleh FullCalendar dan tampilan list tabel
        // Variabel $bookedDates DIDEFINISIKAN di sini
        $bookedDates = $bookings->map(function ($booking) {
            // Pastikan relasi 'user' dan 'facility' tersedia sebelum mengaksesnya
            $facilityName = $booking->facility ? $booking->facility->name : 'N/A';
            $userName = $booking->user ? $booking->user->name : 'N/A';

            $bookingDate = Carbon::parse($booking->booking_date)->format('Y-m-d');
            $bookingTime = Carbon::parse($booking->booking_time)->format('H:i:s');
            // Default hours ke 1 jika tidak ada, untuk menghindari error kalkulasi waktu berakhir
            $bookingHours = $booking->hours ?? 1; 

            // Menyiapkan judul untuk event kalender
            $title = "{$facilityName} - " . Carbon::parse($booking->booking_time)->format('h:i A');

            // Menghitung waktu mulai dan berakhir dalam format ISO8601 untuk FullCalendar
            $startDateTime = Carbon::parse($bookingDate . ' ' . $bookingTime);
            $endDateTime = $startDateTime->copy()->addHours($bookingHours);

            return [
                'title' => $title,
                'start' => $startDateTime->toIso8601String(), 
                'end' => $endDateTime->toIso8601String(),     
                'bookingDate' => $bookingDate,
                'bookingTime' => $bookingTime,
                'bookingHours' => $bookingHours,
                'facilityName' => $facilityName,
                'userName' => $userName,
                'bookingStatus' => $booking->status,
                'bookingAmount' => $booking->amount ?? 0, 
                'bookingPaymentMethod' => $booking->payment_method ?? 'N/A',
                // Menentukan kelas CSS untuk styling event di kalender berdasarkan status booking
                'classNames' => ['fc-event-' . strtolower(str_replace(' ', '-', $booking->status))],
                'borderColor' => 'red', // Warna border default untuk event
            ];
        });

        // Meneruskan variabel 'user' dan 'bookedDates' ke tampilan
        // Variabel $bookedDates DIKIRIMKAN ke tampilan di sini
        return view('admin.calendar.index', compact('user', 'bookedDates'));
    }
}