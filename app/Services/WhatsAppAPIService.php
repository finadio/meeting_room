<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppAPIService
{
    // Variabel untuk Meta/WhatsApp Business Platform API
    protected $metaApiUrl;
    protected $metaAccessToken;
    protected $metaPhoneNumberId;

    // Variabel untuk Qontak API
    protected $qontakApiUrl = 'https://service-chat.qontak.com/api/open/v1'; // Base URL Qontak API
    protected $qontakAccessToken;
    protected $qontakChannelId;

    public function __construct()
    {
        // Inisialisasi untuk Meta/WhatsApp Business Platform API
        $this->metaApiUrl = config('app.whatsapp_api_url');
        $this->metaAccessToken = config('app.whatsapp_access_token');
        $this->metaPhoneNumberId = config('app.whatsapp_phone_number_id');

        // Inisialisasi untuk Qontak API
        $this->qontakAccessToken = config('app.access_token'); // Mengambil dari ACCESS_TOKEN di .env
        $this->qontakChannelId = config('app.channel_id'); // Mengambil dari CHANNEL_ID di .env
    }

    /**
     * Mengirim pesan WhatsApp biasa (non-template) melalui Meta/WhatsApp Business Platform API.
     * Digunakan untuk konfirmasi booking.
     *
     * @param string $to Nomor tujuan (contoh: "628123456789")
     * @param string $message Isi pesan teks
     * @return array Respon dari API
     * @throws \Exception Jika pengiriman gagal
     */
    public function sendWhatsAppNotification($to, $message)
    {
        if (empty($this->metaApiUrl) || empty($this->metaAccessToken) || empty($this->metaPhoneNumberId)) {
            Log::error("WhatsApp Meta API Service: Konfigurasi tidak lengkap (URL, Token, atau Phone Number ID kosong).");
            throw new \Exception("Konfigurasi WhatsApp Meta API tidak lengkap.");
        }

        $url = $this->metaApiUrl . '/' . $this->metaPhoneNumberId . '/messages';
        Log::info("WhatsApp Meta API: Mengirim pesan ke {$to} dengan isi: {$message}");

        try {
            $response = Http::withToken($this->metaAccessToken)->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            $response->throw(); // Melemparkan exception jika status code bukan 2xx

            Log::info("WhatsApp Meta API Response: " . $response->body());
            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $errorMessage = $e->response ? $e->response->body() : $e->getMessage();
            Log::error("WhatsApp Meta API Request Failed: " . $errorMessage);
            throw new \Exception('Gagal mengirim pesan WhatsApp via Meta API: ' . $errorMessage);
        } catch (\Exception $e) {
            Log::error("WhatsApp Meta API Service Error: " . $e->getMessage());
            throw new \Exception("Terjadi kesalahan pada layanan WhatsApp Meta API: " . $e->getMessage());
        }
    }

    /**
     * Mengirim pesan WhatsApp template melalui Qontak API.
     * Digunakan untuk notifikasi admin ke Bu Fitri (agenda harian).
     * Asumsi template memiliki {{1}} untuk tanggal/hari dan {{2}} untuk isi pesan.
     *
     * @param string $toNumber Nomor tujuan (contoh: "628123456789")
     * @param string $templateId ID template dari Qontak (contoh: config('app.agenda_daily_template_id'))
     * @param array $parameters Array asosiatif berisi key-value untuk parameter template (contoh: ['1' => 'Senin, 11 Juli 2025', '2' => 'Detail agenda...'])
     * @param string $toName Nama penerima, akan digunakan di field 'to_name' Qontak. Default 'User'.
     * @return array Respon dari API
     * @throws \Exception Jika pengiriman gagal
     */
    public function sendQontakTemplate(string $toNumber, string $templateId, array $parameters, string $toName = 'User')
    {
        if (empty($this->qontakAccessToken) || empty($this->qontakChannelId) || empty($templateId)) {
            Log::error("WhatsApp Qontak API Service: Konfigurasi tidak lengkap (ACCESS_TOKEN, CHANNEL_ID, atau TEMPLATE_ID kosong).");
            throw new \Exception("Konfigurasi API Qontak tidak lengkap.");
        }

        // Format parameters sesuai dengan kebutuhan Qontak API (indexed body parameters)
        $formattedParameters = [];
        foreach ($parameters as $key => $value) {
            $formattedParameters['body'][] = [
                'key' => (string)$key, // Pastikan key adalah string (e.g., '1', '2')
                'value' => 'latest', // Ini adalah fixed value untuk Qontak body parameters
                'value_text' => $value // Nilai aktual yang akan dimasukkan ke template
            ];
        }

        $payload = [
            'to_number' => $toNumber,
            'to_name' => $toName, // Perbaikan: Gunakan $toName yang non-kosong
            'message_template_id' => $templateId,
            'channel_integration_id' => $this->qontakChannelId,
            'language' => [
                'code' => 'id'
            ],
            'parameters' => $formattedParameters
        ];

        Log::info("WhatsApp Qontak Template Payload: " . json_encode($payload));

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->qontakAccessToken,
                'Content-Type' => 'application/json',
            ])->post($this->qontakApiUrl . '/broadcasts/whatsapp/direct', $payload); // Endpoint yang benar untuk template Qontak

            $response->throw(); // Melemparkan exception jika status code bukan 2xx

            Log::info("WhatsApp Qontak Template Response: " . $response->body());
            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $errorMessage = $e->response ? $e->response->body() : $e->getMessage();
            Log::error("WhatsApp Qontak Template Request Failed: " . $errorMessage);
            throw new \Exception("Gagal mengirim pesan template WhatsApp Qontak: " . $errorMessage);
        } catch (\Exception $e) {
            Log::error("WhatsApp Qontak Template Service Error: " . $e->getMessage());
            throw new \Exception("Terjadi kesalahan pada layanan WhatsApp Qontak: " . $e->getMessage());
        }
    }
}
