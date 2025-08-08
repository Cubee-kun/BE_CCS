<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perencanaan extends Model
{
    protected $fillable = [
        'user_id', 'nama_perusahaan', 'nama_pic', 'narahubung',
        'jenis_kegiatan', 'lokasi', 'jumlah_bibit', 'jenis_bibit',
        'tanggal_pelaksanaan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function implementasi()
    {
        return $this->hasOne(Implementasi::class);
    }
}
