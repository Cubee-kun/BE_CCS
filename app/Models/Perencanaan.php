<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perencanaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'nama_pic',
        'narahubung',
        'jenis_kegiatan',
        'lokasi',
        'jumlah_bibit',
        'jenis_bibit',
        'tanggal_pelaksanaan',
        'lat',
        'long',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tambahkan di app/Models/Perencanaan.php
    public function implementasi()
    {
        return $this->hasOne(Implementasi::class);
    }
}
