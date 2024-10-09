<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'presensi';

    protected $fillable = [
        'nik',
        'tgl_presensi',
        'jam_in',
        'foto_in',
        'lokasi_in',
    ];

    public $timestamps = false; // jika tabel tidak memiliki kolom created_at dan updated_at
}


