<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\Booking; 
use App\Models\Facility; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Statistik Dashboard
        $userCount = User::count();
        $bookingCount = Booking::count();
        $facilityCount = Facility::count();
        $totalPendingBookings = Booking::where('status', 'Menunggu Konfirmasi')->count();
        
        // Data untuk Chart Distribusi Pengguna
        // Menggunakan get() untuk mengambil Collection, lalu mengakses dengan property object
        $userTypes = User::select('user_type', DB::raw('count(*) as count'))
                         ->groupBy('user_type')
                         ->pluck('count', 'user_type');
        
        $userCounts = [
            'Admin' => $userTypes->get('admin', 0), 
            'User' => $userTypes->get('user', 0),
        ];

        // Memuat semua bookings dengan relasi user dan facility
        // PENTING: Pastikan relasi user dan facility DIMUAT DI SINI
        $allBookings = Booking::with(['user', 'facility'])->get(); 

        // Memetakan data bookings ke format yang dibutuhkan oleh tampilan, termasuk untuk catatan
        // Menambahkan penanganan null (menggunakan ?-> dan ??) untuk properti relasi
        $bookedDates = $allBookings->map(function ($booking) {
            $facilityName = $booking->facility->name ?? 'Fasilitas Tidak Dikenal'; // Pastikan relasi facility dimuat
            $userName = $booking->user->name ?? 'Pengguna Tidak Dikenal'; // Pastikan relasi user dimuat

            $bookingDate = Carbon::parse($booking->booking_date)->format('Y-m-d');
            $bookingTime = Carbon::parse($booking->booking_time)->format('H:i:s');
            $bookingHours = $booking->hours ?? 1; 

            // Judul event untuk catatan
            $title = "{$facilityName} - {$userName} (" . Carbon::parse($booking->booking_time)->format('H:i') . ")";

            return [
                'title' => $title,
                // Menggunakan booking_date yang sudah diformat di sini
                'bookingDate' => $bookingDate, 
                'bookingTime' => $bookingTime, 
                'bookingHours' => $bookingHours,
                'facilityName' => $facilityName,
                'userName' => $userName,
                'bookingStatus' => $booking->status,
                'bookingAmount' => $booking->amount ?? 0, 
                'bookingPaymentMethod' => $booking->payment_method ?? 'N/A',
                // Data 'start' dan 'end' diperlukan untuk FullCalendar, namun di dashboard ini tidak ada FullCalendar.
                // Jika Anda ingin menggunakannya lagi, pastikan formatnya benar
                'start' => Carbon::parse($bookingDate . ' ' . $bookingTime)->toIso8601String(),
                'end' => Carbon::parse($bookingDate . ' ' . ($booking->booking_end ?? '00:00:00'))->toIso8601String(), // Pastikan booking_end ada
                'classNames' => ['fc-event-' . strtolower(str_replace(' ', '-', $booking->status))],
            ];
        });
        
        $unreadNotificationCount = Notification::where('is_read', false)->count();

        return view('admin.dashboard', compact(
            'user', // Variabel $user ini diperlukan untuk Auth::user()->name di Blade
            'userCount', 
            'bookingCount', 
            'facilityCount', 
            'totalPendingBookings', 
            'userCounts', 
            'bookedDates', 
            'unreadNotificationCount'
        ));
    }

    public function notifications()
    {
        $notifications = Notification::with('facility.user')->where('is_read', false)->get();
        $unreadNotificationCount = Notification::where('is_read', false)->count();

        return view('admin.notifications.index', compact('notifications', 'unreadNotificationCount'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return redirect()->route('admin.notifications.index');
    }


    public function viewSubmission($id)
    {
        try {
            $facility = Facility::findOrFail($id);

            return view('user.facility_submissions.view', compact('facility'));
        } catch (\Exception $e) {
            return redirect()->route('user.facility_submissions.create')->with('error', 'Facility not found.');
        }
    }

    public function getRoomStatus($id)
    {
        // Dapatkan tanggal dan waktu saat ini
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        // Ambil booking yang sedang berlangsung
        $ongoingBooking = Booking::where('facility_id', $id)
            ->where('booking_date', $currentDate) // Ubah 'date' menjadi 'booking_date'
            ->where('booking_time', '<=', $currentTime)
            ->where('booking_end', '>', $currentTime)
            ->first();

        // Ambil booking mendatang setelah waktu saat ini
        $upcomingBookings = Booking::where('facility_id', $id)
            ->where('booking_date', $currentDate) // Ubah 'date' menjadi 'booking_date'
            ->where('booking_time', '>', $currentTime)
            ->orderBy('booking_time')
            ->get();

        // Struktur data yang dikembalikan
        return response()->json([
            'ongoingBooking' => $ongoingBooking,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}