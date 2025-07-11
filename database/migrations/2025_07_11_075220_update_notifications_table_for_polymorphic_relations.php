<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Schema\Comparator; // Import Comparator

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Hapus kolom facility_submission_id jika ada
            if (Schema::hasColumn('notifications', 'facility_submission_id')) {
                // Periksa apakah foreign key ada sebelum menghapusnya
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $fromTable = $sm->listTableDetails('notifications');
                $foreignKeys = $fromTable->getForeignKeys();

                // Nama foreign key default Laravel biasanya notifications_facility_submission_id_foreign
                $foreignKeyName = 'notifications_facility_submission_id_foreign';

                if (array_key_exists($foreignKeyName, $foreignKeys)) {
                    $table->dropForeign(['facility_submission_id']);
                }
                $table->dropColumn('facility_submission_id');
            }

            // Tambahkan kolom notifiable_type dan notifiable_id untuk relasi polimorfik
            // Pastikan kolom belum ada sebelum menambahkannya
            if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->string('notifiable_type')->after('id');
            }
            if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                $table->unsignedBigInteger('notifiable_id')->after('notifiable_type');
            }
            // Tambahkan indeks hanya jika kedua kolom sudah ada atau baru saja ditambahkan
            if (Schema::hasColumn('notifications', 'notifiable_type') && Schema::hasColumn('notifications', 'notifiable_id')) {
                // Pastikan indeks belum ada sebelum menambahkannya
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes('notifications');
                $indexName = 'notifications_notifiable_type_notifiable_id_index'; // Nama indeks default Laravel

                if (!array_key_exists($indexName, $indexes)) {
                    $table->index(['notifiable_type', 'notifiable_id']);
                }
            }


            // Tambahkan kolom 'type' untuk mengkategorikan notifikasi
            // Pastikan kolom belum ada sebelum menambahkannya
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->after('message')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Hapus kolom yang baru ditambahkan saat rollback
            // Periksa apakah indeks ada sebelum menghapusnya
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('notifications');
            $indexName = 'notifications_notifiable_type_notifiable_id_index'; // Nama indeks default Laravel

            if (array_key_exists($indexName, $indexes)) {
                $table->dropIndex(['notifiable_type', 'notifiable_id']);
            }

            if (Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->dropColumn('notifiable_type');
            }
            if (Schema::hasColumn('notifications', 'notifiable_id')) {
                $table->dropColumn('notifiable_id');
            }
            if (Schema::hasColumn('notifications', 'type')) {
                $table->dropColumn('type');
            }

            // Opsional: Tambahkan kembali facility_submission_id jika diperlukan untuk rollback
            // Ini mungkin tidak diperlukan jika Anda tidak berencana untuk rollback ke versi lama
            // if (!Schema::hasColumn('notifications', 'facility_submission_id')) {
            //     $table->unsignedBigInteger('facility_submission_id')->nullable()->after('user_id');
            //     $table->foreign('facility_submission_id')->references('id')->on('facility_submissions')->onDelete('cascade');
            // }
        });
    }
};

