<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaManual extends Model
{
    use HasFactory;

    protected $fillable = ['agenda_harian_id', 'jam', 'jam_selesai', 'judul', 'lokasi'];

    public function agendaHarian()
    {
        return $this->belongsTo(AgendaHarian::class);
    }
}
