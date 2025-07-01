<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppAPIService
{
    protected $apiUrl;
    protected $accessToken;
    protected $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = env('WHATSAPP_API_URL');
        $this->accessToken = env('WHATSAPP_ACCESS_TOKEN');
        $this->phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
    }

    public function sendWhatsAppNotification($to, $message)
    {
        $url = $this->apiUrl . '/' . $this->phoneNumberId . '/messages';
        $response = Http::withToken($this->accessToken)->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'body' => $message
            ]
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to send WhatsApp message: ' . $response->body());
        }

        return $response->json();
    }
}
