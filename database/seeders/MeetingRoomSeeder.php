<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeetingRoomSeeder extends Seeder
{
    public function run()
    {
        $facilities = [
            [
                'name' => 'Ruang Meeting Premium',
                'description' => 'Ruang meeting premium dengan teknologi modern dan kenyamanan maksimal untuk pertemuan bisnis profesional.',
                'location' => 'Jl. C. Simanjuntak no 26, Jakarta',
                'map_coordinates' => '-6.2088,106.8456',
                'price_per_hour' => '500000',
                'facility_type' => 'meeting_room',
                'opening_time' => '08:00:00',
                'closing_time' => '22:00:00',
                'contact_person' => 'Admin MSA',
                'contact_email' => 'admin@msa.co.id',
                'contact_phone' => '021-1234567',
                'image_path' => 'img/meeting_lobby.jpeg',
                'status' => 'approved',
                'rating' => 4.8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Meeting Lobby',
                'description' => 'Lobby kantor Bank MSA di Jl. C. Simanjuntak no 26 dengan ruang meeting yang nyaman.',
                'location' => 'Lobby Bank MSA, Jl. C. Simanjuntak no 26',
                'map_coordinates' => '-6.2088,106.8456',
                'price_per_hour' => '300000',
                'facility_type' => 'meeting_room',
                'opening_time' => '08:00:00',
                'closing_time' => '20:00:00',
                'contact_person' => 'Admin MSA',
                'contact_email' => 'admin@msa.co.id',
                'contact_phone' => '021-1234567',
                'image_path' => 'img/meeting_lobby.jpeg',
                'status' => 'approved',
                'rating' => 4.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Meeting Lt 2',
                'description' => 'Lantai 2 Kantor Bank MSA Jl. C. Simanjuntak no 26 dengan ruang meeting yang luas.',
                'location' => 'Lantai 2 Bank MSA, Jl. C. Simanjuntak no 26',
                'map_coordinates' => '-6.2088,106.8456',
                'price_per_hour' => '400000',
                'facility_type' => 'meeting_room',
                'opening_time' => '08:00:00',
                'closing_time' => '21:00:00',
                'contact_person' => 'Admin MSA',
                'contact_email' => 'admin@msa.co.id',
                'contact_phone' => '021-1234567',
                'image_path' => 'img/Meeting_lt2.jpeg',
                'status' => 'approved',
                'rating' => 4.7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Hapus data lama jika ada
        DB::table('facilities')->where('facility_type', 'meeting_room')->delete();
        
        // Insert data baru
        DB::table('facilities')->insert($facilities);
    }
} 