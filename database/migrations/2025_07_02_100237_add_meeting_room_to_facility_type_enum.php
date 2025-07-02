<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum facility_type untuk menambahkan 'meeting_room'
        DB::statement("ALTER TABLE facilities MODIFY COLUMN facility_type ENUM('indoor', 'outdoor', 'meeting_room') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum asli
        DB::statement("ALTER TABLE facilities MODIFY COLUMN facility_type ENUM('indoor', 'outdoor') NULL");
    }
};
