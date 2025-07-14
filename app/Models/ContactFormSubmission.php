<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactFormSubmission extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'email', 'subject', 'message', 'is_read', 'is_replied', 'replied_at', 'reply_message', 'replied_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
