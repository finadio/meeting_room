<?php

namespace App\Models; // INI HARUS App\Models; BUKAN App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Pastikan ini diimpor
use App\Models\Booking; // Pastikan ini diimpor
use App\Models\ContactFormSubmission; // Pastikan ini diimpor
use Illuminate\Support\Str; // Pastikan ini diimpor jika menggunakan Str::limit

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'notifiable_type', // Kolom baru untuk tipe model terkait
        'notifiable_id',   // Kolom baru untuk ID model terkait
        'message',
        'type',            // Kolom baru untuk kategori notifikasi
        'is_read',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent notifiable model (e.g., Booking, ContactFormSubmission, User).
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    // Anda bisa menambahkan scope atau helper method di sini
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function getNotificationDetailsAttribute()
    {
        $details = [];
        switch ($this->type) {
            case 'new_booking':
            case 'booking_status_update':
                if ($this->notifiable instanceof Booking) {
                    $details['title'] = 'Pemesanan Baru: ' . $this->notifiable->meeting_title;
                    $details['description'] = 'Pengguna ' . $this->notifiable->user->name . ' telah membuat pemesanan untuk ' . $this->notifiable->facility->name . ' pada tanggal ' . $this->notifiable->booking_date . ' jam ' . $this->notifiable->booking_time . '.';
                    $details['link'] = route('admin.bookings.show', $this->notifiable->id);
                    $details['icon'] = 'fas fa-calendar-check'; // Contoh ikon
                    $details['status'] = $this->notifiable->status;
                }
                break;
            case 'contact_form_submission':
                if ($this->notifiable instanceof ContactFormSubmission) {
                    $details['title'] = 'Form Kontak Baru dari ' . $this->notifiable->name;
                    $details['description'] = 'Pesan: "' . \Illuminate\Support\Str::limit($this->notifiable->message, 50) . '"';
                    $details['link'] = route('admin.contact.show', $this->notifiable->id); // Asumsi ada route admin.contact.show
                    $details['icon'] = 'fas fa-envelope'; // Contoh ikon
                }
                break;
            case 'new_user_registration':
                if ($this->notifiable instanceof User) {
                    $details['title'] = 'Pengguna Baru Terdaftar: ' . $this->notifiable->name;
                    $details['description'] = 'Email: ' . $this->notifiable->email;
                    $details['link'] = route('admin.users.show', $this->notifiable->id); // Asumsi ada route admin.users.show
                    $details['icon'] = 'fas fa-user-plus'; // Contoh ikon
                }
                break;
            // Tambahkan case lain untuk tipe notifikasi yang berbeda
            default:
                $details['title'] = 'Notifikasi Umum';
                $details['description'] = $this->message;
                $details['link'] = '#';
                $details['icon'] = 'fas fa-bell';
                break;
        }
        return $details;
    }
}
