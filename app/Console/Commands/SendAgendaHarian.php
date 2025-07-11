<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AgendaHarian;
use App\Models\Booking;
use Carbon\Carbon;
use App\Services\WhatsAppAPIService;
use App\Http\Controllers\Admin\AdminSendMessageController;

class SendAgendaHarian extends Command
{
    /**
     * Nama dan signature dari perintah konsol.
     *
     * @var string
     */
    protected $signature = 'agenda:send-harian';

    /**
     * Deskripsi perintah konsol.
     *
     * @var string
     */
    protected $description = 'Kirim agenda harian otomatis ke Bu Fitri via WhatsApp';

    /**
     * Jalankan perintah konsol.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today();
        $agenda = AgendaHarian::where('tanggal', $today)->first();
        if (!$agenda) {
            $this->info('Tidak ada agenda harian untuk hari ini.');
            return 0;
        }

        $bookings = Booking::where('booking_date', $today->toDateString())
            ->with(['facility', 'user']) // Tambahkan eager loading
            ->orderBy('booking_time')
            ->get();

        // Instansiasi controller untuk mengakses generateMessageBody
        $adminSendMessageController = app(AdminSendMessageController::class);

        // Siapkan parameter untuk template Qontak
        // Parameter 1: Hari, Tanggal
        $key1 = $agenda->tanggal->isoFormat('dddd, D MMMM Y');
        // Parameter 2: Isi Pesan (agenda detail)
        $key2 = $adminSendMessageController->generateMessageBody($agenda, $bookings);

        // Perbaikan di sini: Ambil nomor Bu Fitri dari konfigurasi (file .env)
        // Menggunakan nomor khusus Bu Fitri dari .env
        $nomorBuFitri = config('app.bu_fitri_whatsapp_number');
        $templateId = config('app.agenda_daily_template_id'); // Gunakan template ID Qontak untuk agenda harian

        try {
            // Panggil sendQontakTemplate dari service Anda
            // Tambahkan nama penerima (misal: "Bu Fitri") sebagai parameter keempat
            app(WhatsAppAPIService::class)->sendQontakTemplate(
                $nomorBuFitri,
                $templateId,
                [
                    '1' => $key1, // Menggunakan key '1' untuk parameter pertama {{1}}
                    '2' => $key2  // Menggunakan key '2' untuk parameter kedua {{2}}
                ],
                'Bu Fitri' // Menambahkan nama penerima untuk parameter to_name
            );

            $agenda->status_kirim = 'terkirim';
            $agenda->waktu_kirim = now();
            $agenda->save();
            $this->info('Pesan agenda harian berhasil dikirim ke Bu Fitri!');
        } catch (\Exception $e) {
            $agenda->status_kirim = 'gagal';
            $agenda->waktu_kirim = now();
            $agenda->save();
            $this->error('Pesan agenda harian gagal dikirim: ' . $e->getMessage());
        }
        return 0;
    }
}
