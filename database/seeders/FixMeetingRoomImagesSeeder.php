<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixMeetingRoomImagesSeeder extends Seeder
{
    public function run()
    {
        // Update semua fasilitas meeting room dengan path gambar yang benar
        DB::table('facilities')
            ->where('facility_type', 'meeting_room')
            ->update([
                'image_path' => 'img/meeting_lobby.jpeg'
            ]);
            
        echo "Updated meeting room images to use meeting_lobby.jpeg\n";
        
        // Tampilkan data yang sudah diupdate
        $facilities = DB::table('facilities')
            ->where('facility_type', 'meeting_room')
            ->get(['id', 'name', 'image_path']);
            
        foreach($facilities as $facility) {
            echo "ID: {$facility->id} - Name: {$facility->name} - Image: {$facility->image_path}\n";
        }
    }
} 