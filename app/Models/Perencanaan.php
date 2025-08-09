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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
