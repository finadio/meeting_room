<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;


class StatusController extends Controller
{

// public function showRoomStatus()
// {
//     // \Log::info('Facility Bookings:', ['data' => $facilityBookings->toArray()]);
//     // Mendapatkan tanggal hari ini
//     date_default_timezone_set('Asia/Jakarta'); // Pastikan zona waktu sudah diatur
//     $today = Carbon::today();

//     // Mengambil booking yang terjadi hari ini
//     $bookings = Booking::whereDate('booking_date', $today)
//                         ->orderBy('booking_date')
//                         ->orderBy('booking_time')
//                         ->get()
//                         ->groupBy('facility_id'); // Mengelompokkan berdasarkan facility_id;

//     return view('dashboard.room-status', compact('bookings'));
    

// }
// public function update()
// {
//     // Ambil data booking untuk hari ini dan group berdasarkan facility_id
//     $bookings = Booking::whereDate('booking_date', now()->format('Y-m-d'))
//                 ->orderBy('booking_time', 'asc')
//                 ->get()
//                 ->groupBy('facility_id');

//     \Log::info('Update room status called');

//     // Kembalikan partial view, hanya data tabel tanpa layout
//     $html = view('partials.room-status-table', compact('bookings'))->render();

//     return response()->json(['html' => $html]);
// }
    public function showRoomStatus()
    {
        // Pastikan zona waktu telah diatur
        date_default_timezone_set('Asia/Jakarta');

        // Mendapatkan tanggal hari ini
        $today = Carbon::today();

        // Mengambil booking yang terjadi hari ini dan mengelompokkan berdasarkan facility_id
        $bookings = Booking::with('facility') // Pastikan 'facility' adalah relasi yang didefinisikan di model Booking
                    ->whereDate('booking_date', $today)
                    ->orderBy('booking_time')
                    ->get()
                    ->groupBy('facility_id'); // Mengelompokkan berdasarkan facility_id

        \Log::info('ShowRoomStatus Bookings: ' . json_encode($bookings));

        // Kirim data bookings ke view
        return view('dashboard.room-status', compact('bookings'));
    }

    public function update()
    {
        \Log::info('Memuat data room-status');
        
        // Mendapatkan data booking terbaru
        $today = Carbon::today();
        $bookings = Booking::with('facility') // Pastikan 'facility' adalah relasi yang didefinisikan di model Booking
                    ->whereDate('booking_date', $today) // Hanya booking untuk hari ini
                    ->orderBy('booking_time', 'asc')
                    ->get()
                    ->groupBy('facility_id'); // Kelompokkan berdasarkan facility_id
        
        \Log::info('Data bookings saat update: ' . json_encode($bookings));

        // Render ulang view dari data yang diperbarui
        $html = view('partials.room-status', compact('bookings'))->render();

        // Kembalikan data dalam format JSON
        return response()->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }
}
