<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agenda_harians', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('ootd_cewek')->nullable();
            $table->string('ootd_cowok')->nullable();
            $table->json('agenda_manual')->nullable(); // agenda tambahan manual, array json
            $table->enum('status_kirim', ['belum', 'terkirim', 'gagal'])->default('belum');
            $table->timestamp('waktu_kirim')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_harians');
    }
};
