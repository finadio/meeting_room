<?php

namespace App\Http\Controllers\Admin; // Namespace yang benar untuk Admin Dashboard Controller

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\ContactFormSubmission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk mengakses Auth facade

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Dapatkan user yang sedang login
        $user = Auth::user(); // Tambahkan baris ini

        // Hitung total pengguna
        $userCount = User::count();
        // Hitung pengguna baru dalam 30 hari terakhir
        $newUsersCount = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Hitung total booking
        $bookingCount = Booking::count();
        // Hitung total fasilitas
        $facilityCount = Facility::count();

        // Hitung total pemesanan menunggu konfirmasi
        $totalPendingBookings = Booking::where('status', 'Menunggu Konfirmasi')->count(); // Tambahkan baris ini

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
            // Karena booking_date, booking_time, booking_end sudah di-cast sebagai Carbon object di model Booking,
            // kita bisa langsung mengaksesnya.
            // Kita perlu menggabungkan tanggal dari booking_date dengan waktu dari booking_time/booking_end.
            $startDateTime = $booking->booking_date->setTime($booking->booking_time->hour, $booking->booking_time->minute, $booking->booking_time->second);
            $endDateTime = $booking->booking_date->setTime($booking->booking_end->hour, $booking->booking_end->minute, $booking->booking_end->second);

            return [
                'title' => $booking->meeting_title ?? $booking->facility->name,
                'start' => $startDateTime->toIso8601String(),
                'end' => $endDateTime->toIso8601String(),
                'status' => $booking->status, // Atau status booking lainnya
                'color' => $booking->status === 'Disetujui' ? '#4A6DFB' : '#FFC107', // Warna event berdasarkan status
            ];
        });

        // Hitung notifikasi yang belum dibaca untuk ditampilkan di dashboard (opsional)
        $unreadNotificationCount = Notification::where('is_read', false)->count();

        return view('admin.dashboard', compact(
            'user', // Pastikan variabel user diteruskan
            'userCount', 
            'bookingCount', 
            'facilityCount', 
            'newUsersCount', 
            'totalPendingBookings', // Pastikan variabel ini diteruskan
            'formattedRevenue', 
            'userCounts', 
            'bookedDates', 
            'unreadNotificationCount'
        ));
    }

    /**
     * Menampilkan daftar semua notifikasi untuk admin.
     *
     * @return \Illuminate\View\View
     */
    public function notifications()
    {
        // Ambil semua notifikasi, urutkan berdasarkan yang belum dibaca terlebih dahulu, lalu tanggal terbaru
        // Eager load relasi 'notifiable' dan nested relasinya
        $notifications = Notification::with(['notifiable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Booking::class => ['facility', 'user'], // Jika notifiable adalah Booking, eager load facility dan user
                ContactFormSubmission::class => [], // ContactFormSubmission mungkin tidak punya relasi nested yang perlu di-eager load
                User::class => [], // User mungkin tidak punya relasi nested yang perlu di-eager load
            ]);
        }])
        ->orderBy('is_read', 'asc') // Notifikasi belum dibaca di atas
        ->orderBy('created_at', 'desc')
        ->paginate(10); // Sesuaikan jumlah notifikasi per halaman

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        $notification->markAsRead(); // Menggunakan helper method dari model Notification

        return redirect()->back()->with('success', 'Notifikasi berhasil ditandai sudah dibaca.');
    }

    // Metode viewSubmission yang lama telah dihapus atau dikomentari
    // karena logika detail notifikasi sekarang ditangani secara polimorfik
    // melalui accessor di model Notification dan link di view Blade.
    /*
    public function viewSubmission(Notification $notification)
    {
        // Contoh: Jika Anda masih ingin menggunakan metode ini untuk tujuan umum
        // misalnya, untuk menampilkan detail notifikasi berdasarkan notifiable_type
        // Anda perlu menyesuaikan logika di sini.
        // Contoh:
        // if ($notification->notifiable_type === 'App\Models\FacilitySubmission') {
        //     return redirect()->route('admin.facility_submissions.view', $notification->notifiable_id);
        // } elseif ($notification->notifiable_type === 'App\Models\Booking') {
        //     return redirect()->route('admin.bookings.show', $notification->notifiable_id);
        // }
        // return redirect()->back()->with('error', 'Tipe notifikasi tidak didukung untuk tampilan detail.');
    }
    */
}
