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
    public function index(Request $request)
    {
        $user = auth()->user();

        // Ambil SEMUA booking, tanpa filter status apapun
        $bookings = Booking::with(['user', 'facility'])->get();

        // Menghitung statistik untuk sidebar (ini tetap di backend)
        $today = Carbon::today();
        $todayBookings = Booking::whereDate('booking_date', $today)->count();
        $approvedBookings = Booking::where('status', 'Disetujui')->count();
        $pendingBookings = Booking::where('status', 'Menunggu Konfirmasi')->count();

        // Memetakan data $bookings ke format yang dibutuhkan oleh FullCalendar dan tampilan list tabel
        $bookedDates = $bookings->map(function ($booking) {
            $facilityName = $booking->facility ? $booking->facility->name : 'N/A';
            $userName = $booking->user ? $booking->user->name : 'N/A';

            $bookingDate = Carbon::parse($booking->booking_date)->format('Y-m-d');
            $bookingTime = Carbon::parse($booking->booking_time)->format('H:i:s');
            $bookingHours = $booking->hours ?? 1;

            $title = "{$facilityName} - " . Carbon::parse($booking->booking_time)->format('h:i A');

            $startDateTime = Carbon::parse($bookingDate . ' ' . $bookingTime);
            // Gunakan booking_end jika tersedia, jika tidak hitung dari bookingHours
            $endDateTime = $booking->booking_end ? Carbon::parse($bookingDate . ' ' . $booking->booking_end) : $startDateTime->copy()->addHours($bookingHours);

            return [
                'id' => $booking->id, // Penting untuk detail modal
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
                'borderColor' => 'transparent', // Biarkan eventColor FullCalendar yang menangani
                'booking_end' => $booking->booking_end ? Carbon::parse($booking->booking_end)->format('H:i:s') : null, // Penting untuk filter JS
            ];
        });

        // Teruskan variabel-variabel ini ke tampilan
        return view('admin.calendar.index', compact('user', 'bookedDates', 'todayBookings', 'approvedBookings', 'pendingBookings'));
    }
}