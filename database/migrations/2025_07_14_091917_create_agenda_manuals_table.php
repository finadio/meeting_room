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
        Schema::create('agenda_manuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_harian_id')->constrained('agenda_harians')->onDelete('cascade');
            $table->string('jam')->nullable();
            $table->string('jam_selesai')->nullable();
            $table->string('judul');
            $table->string('lokasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_manuals');
    }
};
