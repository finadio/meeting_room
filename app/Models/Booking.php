<?php

namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use App\Models\User;
    use App\Models\Facility;

    class Booking extends Model
    {
        use HasFactory;

        protected $fillable = [
            'user_id',
            'facility_id',
            'amount',
            'user_name',
            'email',
            'contact_number',
            'booking_date',
            'booking_time',
            'booking_end',
            'meeting_title',
            'group_name',
            'status',
            'payment_method',
            'ratings',
            'reviews',
            'wa_sent',
            'check_in',
            'check_out',
            'is_checked_in',
        ];

        // Tambahkan atau modifikasi bagian $casts ini
        protected $casts = [
            'booking_date' => 'date', // Cukup 'date' jika hanya tanggal
            'booking_time' => 'datetime', // 'datetime' untuk waktu
            'booking_end' => 'datetime', // 'datetime' untuk waktu
            'check_in' => 'datetime',
            'check_out' => 'datetime',
        ];

        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function facility()
        {
            return $this->belongsTo(Facility::class);
        }

        public function hasReviews()
        {
            return !is_null($this->reviews);
        }
    }
    