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

        // Group bookings per tanggal untuk kalender (hanya booking dengan tanggal valid)
        $grouped = $bookings->filter(function($booking) {
            return !empty($booking->booking_date);
        })->groupBy(function($booking) {
            return Carbon::parse($booking->booking_date)->format('Y-m-d');
        });

        $bookedDates = $grouped->map(function($items, $date) {
            $bookingsArr = $items->map(function($booking) {
                $facilityName = $booking->facility ? $booking->facility->name : 'N/A';
                $userName = $booking->user ? $booking->user->name : 'N/A';
                return [
                    'id' => $booking->id,
                    'facilityName' => $facilityName,
                    'userName' => $userName,
                    'bookingDate' => Carbon::parse($booking->booking_date)->format('Y-m-d'),
                    'bookingTime' => Carbon::parse($booking->booking_time)->format('H:i:s'),
                    'bookingHours' => $booking->hours ?? 1,
                    'bookingStatus' => $booking->status,
                    'bookingAmount' => $booking->amount ?? 0,
                    'bookingPaymentMethod' => $booking->payment_method ?? 'N/A',
                ];
            })->toArray();
            
            // Debug: log setiap event yang dibuat
            \Log::info("Creating event for date: $date with " . count($bookingsArr) . " bookings");
            
            return [
                'title' => count($bookingsArr) . ' Booking',
                'start' => $date,
                'end' => $date,
                'allDay' => true,
                'extendedProps' => [
                    'bookings' => $bookingsArr
                ],
            ];
        })->values();

        // Fallback: jika $bookedDates kosong, buat event per booking (supaya tidak blank)
        if ($bookedDates->isEmpty() && $bookings->count() > 0) {
            $bookedDates = $bookings->map(function($booking) {
                $facilityName = $booking->facility ? $booking->facility->name : 'N/A';
                $userName = $booking->user ? $booking->user->name : 'N/A';
                $date = Carbon::parse($booking->booking_date)->format('Y-m-d');
                return [
                    'title' => '1 Booking',
                    'start' => $date,
                    'end' => $date,
                    'allDay' => true,
                    'extendedProps' => [
                        'bookings' => [[
                            'id' => $booking->id,
                            'facilityName' => $facilityName,
                            'userName' => $userName,
                            'bookingDate' => $date,
                            'bookingTime' => Carbon::parse($booking->booking_time)->format('H:i:s'),
                            'bookingHours' => $booking->hours ?? 1,
                            'bookingStatus' => $booking->status,
                            'bookingAmount' => $booking->amount ?? 0,
                            'bookingPaymentMethod' => $booking->payment_method ?? 'N/A',
                        ]]
                    ],
                ];
            });
        }

        // Data untuk daftar pemesanan (sidebar) tetap per booking
        $allBookings = $bookings->map(function ($booking) {
            $facilityName = $booking->facility ? $booking->facility->name : 'N/A';
            $userName = $booking->user ? $booking->user->name : 'N/A';
            return [
                'id' => $booking->id,
                'facilityName' => $facilityName,
                'userName' => $userName,
                'bookingDate' => Carbon::parse($booking->booking_date)->format('Y-m-d'),
                'bookingTime' => Carbon::parse($booking->booking_time)->format('H:i:s'),
                'bookingHours' => $booking->hours ?? 1,
                'bookingStatus' => $booking->status,
                'bookingAmount' => $booking->amount ?? 0,
                'bookingPaymentMethod' => $booking->payment_method ?? 'N/A',
            ];
        });

        // Debug: log data untuk memastikan format benar
        \Log::info('AdminCalendarController - Total bookings: ' . $bookings->count());
        \Log::info('AdminCalendarController - Total bookedDates: ' . $bookedDates->count());
        if ($bookedDates->count() > 0) {
            \Log::info('AdminCalendarController - First bookedDate: ' . json_encode($bookedDates->first()));
        }

        // Teruskan variabel-variabel ini ke tampilan
        return view('admin.calendar.index', [
            'user' => $user,
            'bookedDates' => $bookedDates,
            'allBookings' => $allBookings,
            'todayBookings' => $todayBookings,
            'approvedBookings' => $approvedBookings,
            'pendingBookings' => $pendingBookings,
        ]);
    }
}