<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaHarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'ootd_cewek',
        'ootd_cowok',
        'agenda_manual',
        'status_kirim',
        'waktu_kirim',
    ];

    protected $casts = [
        'agenda_manual' => 'array',
        'waktu_kirim' => 'datetime',
        'tanggal' => 'date',
    ];
}
