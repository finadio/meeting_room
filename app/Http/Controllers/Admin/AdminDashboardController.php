<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Hitung total pengguna
        $userCount = User::count();
        // Hitung pengguna baru dalam 30 hari terakhir
        $newUsersCount = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Hitung total booking
        $bookingCount = Booking::count();
        // Hitung total fasilitas
        $facilityCount = Facility::count();

        // Hitung total pendapatan (sum dari kolom 'amount' di tabel bookings)
        // Pastikan kolom 'amount' di tabel 'bookings' diisi dengan nilai yang benar
        $totalRevenue = Booking::sum('amount');
        // Format pendapatan menjadi mata uang (contoh: Rp 12.000.000)
        $formattedRevenue = 'Rp ' . number_format($totalRevenue, 0, ',', '.');


        // Data untuk grafik User Distribution
        $userCounts = [
            'Admin' => User::where('user_type', 'admin')->count(),
            'User' => User::where('user_type', 'user')->count(),
            // Tambahkan tipe user lain jika ada
        ];

        // Data untuk kalender booking
        $bookedDates = Booking::all()->map(function($booking) {
            return [
                'title' => $booking->meeting_title ?? $booking->facility->name,
                'start' => Carbon::parse($booking->booking_date . ' ' . $booking->booking_time)->toIso8601String(),
                'end' => Carbon::parse($booking->booking_date . ' ' . $booking->booking_end)->toIso8601String(),
                'status' => $booking->status, // Atau status booking lainnya
                'color' => $booking->status === 'Disetujui' ? '#4A6DFB' : '#FFC107', // Warna event berdasarkan status
            ];
        });

        $unreadNotificationCount = Notification::where('is_read', false)->count();

        return view('admin.dashboard', compact(
            'userCount', 'bookingCount', 'facilityCount', 'newUsersCount',
            'formattedRevenue', 'userCounts', 'bookedDates', 'unreadNotificationCount'
        ));
    }
}