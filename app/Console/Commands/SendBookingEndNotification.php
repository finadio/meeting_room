<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Facility;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppAPIService; // Misal ada layanan WA khusus
use App\Http\Controllers\SendMessage;

class SendBookingEndNotification extends Command
{
    protected $signature = 'booking:send-end-notification';

    protected $description = 'Kirim notifikasi WhatsApp jika booking_end kurang dari 10 menit lagi';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        Log::info('Perintah booking:send-end-notification dijalankan');
        // $upcomingEndBookings = Booking::where('status', 'Disetujui')
        //     ->where('booking_end', '>', $now)
        //     ->where('booking_end', '<=', $now->copy()->addMinutes(10))
        //     ->whereNull('check_out')
        //     ->get();
        //     // dd($upcomingEndBookings);
        // Log::info('SQL Query: ' . $upcomingEndBookingsQuery->toSql());
        // Log::info('Bindings: ' . json_encode($upcomingEndBookingsQuery->getBindings()));
        // Log::info('Booking yang akan berakhir dalam 10 menit: ', ['bookings' => $upcomingEndBookings]);
        $upcomingEndBookingsQuery = Booking::where('status', 'Disetujui')
            ->where('booking_end', '>', $now)
            ->where('booking_end', '<=', $now->copy()->addMinutes(10))
            ->whereNull('check_out');

        Log::info('SQL Query: ' . $upcomingEndBookingsQuery->toSql());
        Log::info('Bindings: ' . json_encode($upcomingEndBookingsQuery->getBindings()));

        $upcomingEndBookings = $upcomingEndBookingsQuery->get();
        Log::info('Booking End Query Result: ' . json_encode($upcomingEndBookings));

        foreach ($upcomingEndBookings as $booking) {
            // Cek jika tidak ada jadwal booking lain di ruangan yang sama setelahnya
            $nextBooking = Booking::where('facility_id', $booking->facility_id)
                ->where('booking_date', $booking->booking_date)
                ->where('booking_time', '>', $booking->booking_end)
                ->first();
            
            $canExtend = $nextBooking ? false : true;

            // Kirim Notifikasi WA
            $token = env('ACCESS_TOKEN');
            // Ambil data booking berdasarkan ID
            // $booking = Booking::findOrFail($bookingId);
            
            // Ambil nomor WhatsApp dan nama pengguna
            $no_wa = $booking->user->contact_number;  // Pastikan ini mengarah ke nomor telepon pengguna
            $name = $booking->user->name;
            $message = ["1" => "Reminder: Your booking will end in 10 minutes."];
            
            // Kirim notifikasi WhatsApp
            $sendMessage = new SendMessage();
            Log::info('Mempersiapkan pengiriman WhatsApp untuk booking ' . $booking->id);
            Log::info('Data yang dikirim:', ['no_wa' => $no_wa, 'message' => $message]);
            $response = $sendMessage->sendMessageAttemp($no_wa, $name, [['key' => 1, 'value' => 'app', 'value_text' => 'Booking Meeting room'], 
            ['key' => 2, 'value' => 'nama', 'value_text' => "Anda " . " untuk ruang " . $booking->facility->name . ", 10 Menit lagi akan berakhir " ]]);
        
            // if ($response['status'] !== 'success') {
            //     Log::error('Error sending WhatsApp message', [
            //         'user' => $name,
            //         'booking_id' => $booking->id,
            //         'error' => $response['error']
            //     ]);
            // } else {
            //     Log::info("Notifikasi WA berhasil dikirim ke user {$booking->user->name} untuk booking {$booking->id}");
            // }
            Log::info('Respons API WhatsApp:', ['response' => $response]);
            // Log untuk melihat aktivitasnya
            // Log::info("Notifikasi WA dikirim ke user {$booking->user->name} untuk booking {$booking->id}");
            // Log::info("Respon dari API WhatsApp: " . json_encode($response));
            
        }

        return 0;
    }
}
