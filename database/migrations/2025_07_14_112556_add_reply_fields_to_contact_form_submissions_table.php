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
        Schema::table('contact_form_submissions', function (Blueprint $table) {
            $table->boolean('is_replied')->default(false);
            $table->timestamp('replied_at')->nullable();
            $table->text('reply_message')->nullable();
            $table->unsignedBigInteger('replied_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_form_submissions', function (Blueprint $table) {
            $table->dropColumn(['is_replied', 'replied_at', 'reply_message', 'replied_by']);
        });
    }
};
