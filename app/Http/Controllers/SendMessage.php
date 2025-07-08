<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; // Pastikan Log diimpor

class SendMessage extends Controller
{
    // Tambahkan parameter $templateId dengan nilai default null
    public function sendMessageAttemp($no_wa, $name, $token, $templateId = null)
    {
        // Mengambil ACCESS_TOKEN menggunakan config()
        $accessToken = config('app.access_token');
        
        // Log untuk memeriksa apakah token yang diambil dari .env benar
        \Log::info('ACCESS_TOKEN: ' . $accessToken);

        $curl = curl_init();

        // Gunakan templateId yang diteruskan jika ada, jika tidak, gunakan default dari .env
        $finalTemplateId = $templateId ?? getenv('NOTIF_APPROVAL_TEMPLATE_ID');
        \Log::info("Message Template ID (used): " . $finalTemplateId);  // Log nilai template ID yang digunakan
        
        $postData = json_encode([
            'to_number' => $no_wa,
            'to_name' => $name,
            'message_template_id' => $finalTemplateId,  // Gunakan $finalTemplateId di sini
            'channel_integration_id' => getenv('CHANNEL_ID'),
            'language' => [
                'code' => 'id'
            ],
            'parameters' => [
                'body' => $token
            ]
        ]);
        
        // Log untuk memastikan JSON yang dikirim sesuai
        \Log::info("Post Data: " . $postData);

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postData, // Gunakan postData yang telah ter-encode sebelumnya
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $accessToken, // Menggunakan token dari config()
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // Log error jika terjadi masalah pada cURL
            \Log::error("cURL Error #: " . $err);
            return $err;
        } else {
            // Log untuk mencetak response dari API
            \Log::info("WhatsApp API Response: " . $response);

            // Mengembalikan respon agar bisa dicek di tempat lain (optional)
            return $response;
        }
    }
}