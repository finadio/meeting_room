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

        public function getRoomStatusAttribute()
        {
            $currentTime = now()->setTimezone('Asia/Jakarta');
            $bookingDate = $this->booking_date instanceof \Carbon\Carbon ? $this->booking_date->format('Y-m-d') : date('Y-m-d', strtotime($this->booking_date));
            $startTime = \Carbon\Carbon::parse($bookingDate . ' ' . (is_object($this->booking_time) ? $this->booking_time->format('H:i:s') : $this->booking_time));
            $endTime = \Carbon\Carbon::parse($bookingDate . ' ' . (is_object($this->booking_end) ? $this->booking_end->format('H:i:s') : $this->booking_end));

            if ($this->check_out) {
                return 'Selesai';
            } elseif ($currentTime->between($startTime, $endTime) && ($this->is_checked_in ?? false)) {
                return 'Sedang Berlangsung';
            } elseif ($currentTime->greaterThan($endTime) && !($this->check_out ?? false)) {
                return 'Extend Waktu';
            } else {
                return 'Belum Mulai';
            }
        }
    }
    