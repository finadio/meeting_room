<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckBookingStatus extends Command
{
    protected $signature = 'booking:check-status';
    protected $description = 'Check and update booking statuses (auto check-in, auto complete)';

    public function handle()
    {
        Log::info('Console command booking:check-status started.');
        $now = Carbon::now();

        // --- Logika untuk AUTO CHECK-IN (Jika tidak ada check-in manual) ---
        // Cari booking yang statusnya 'Disetujui', belum di-check-in (is_checked_in = 0),
        // dan waktu mulai sudah tiba atau terlewati hari ini.
        $bookingsToAutoCheckIn = Booking::where('status', 'Disetujui')
            ->where('is_checked_in', 0)
            ->whereDate('booking_date', $now->toDateString())
            ->whereTime('booking_time', '<=', $now->toTimeString())
            ->get();

        foreach ($bookingsToAutoCheckIn as $booking) {
            // Opsional: Pastikan tidak ada booking sebelumnya yang masih berlangsung di fasilitas yang sama
            // Ini penting untuk ruangan yang sama agar tidak ada overlap jika check-out sebelumnya belum tercatat
            $previousOngoingBooking = Booking::where('facility_id', $booking->facility_id)
                ->where('booking_date', $booking->booking_date)
                ->where('booking_time', '<', $booking->booking_time)
                ->where('booking_end', '>', $now->toTimeString()) // Cek yang berakhir di masa depan dari waktu mulai booking ini
                ->whereNull('check_out') // Belum check-out
                ->where('status', '!=', 'Selesai') // Status belum selesai
                ->first();

            if (!$previousOngoingBooking) {
                // Lakukan auto check-in
                $booking->check_in = $now;
                $booking->is_checked_in = 1;
                // Anda mungkin ingin mengubah status menjadi 'On Progress' atau semacamnya di sini
                // $booking->status = 'On Progress'; // Jika Anda punya status ini di enum
                $booking->save();
                Log::info("Booking ID {$booking->id} for facility {$booking->facility_id} automatically checked in at {$now}.");
            } else {
                Log::info("Skipped auto check-in for Booking ID {$booking->id} due to previous ongoing booking ID {$previousOngoingBooking->id} at facility {$booking->facility_id}.");
                // Anda mungkin ingin menambahkan logika untuk menandai booking ini sebagai 'konflik'
            }
        }

        // --- Logika untuk AUTO COMPLETE (Jika waktu berakhir dan belum check-out manual) ---
        // Cari booking yang sudah di-check-in, belum di-check-out, dan waktu berakhirnya sudah lewat.
        $bookingsToAutoFinish = Booking::where('is_checked_in', 1) // Sudah di-check-in
            ->whereNull('check_out') // Belum di-check-out manual
            ->where('status', '!=', 'Selesai') // Belum berstatus 'Selesai'
            ->whereDate('booking_date', '<=', $now->toDateString()) // Tanggal booking hari ini atau sebelumnya
            ->where(function($query) use ($now) {
                // Jika tanggal sama, waktu berakhir harus sudah lewat
                $query->whereDate('booking_date', $now->toDateString())
                      ->whereTime('booking_end', '<', $now->toTimeString())
                      // ATAU jika tanggal booking sudah lewat dari hari ini
                      ->orWhereDate('booking_date', '<', $now->toDateString());
            })
            ->get();

        foreach ($bookingsToAutoFinish as $booking) {
            // Langsung set status menjadi 'Selesai' dan isi check_out
            $booking->status = 'Selesai';
            $booking->check_out = $now; // Set waktu check-out otomatis
            $booking->save();
            Log::info("Booking ID {$booking->id} for facility {$booking->facility_id} automatically completed.");
        }

        Log::info('Console command booking:check-status completed.');
    }
}