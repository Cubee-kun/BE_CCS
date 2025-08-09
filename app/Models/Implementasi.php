<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Implementasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'perencanaan_id',
        'nama_perusahaan_sesuai',
        'lokasi_sesuai',
        'jenis_kegiatan_sesuai',
        'jumlah_bibit_sesuai',
        'jenis_bibit_sesuai',
        'tanggal_sesuai',
        'pic_koorlap',
        'dokumentasi_kegiatan',
        'geotagging',
    ];

    public function perencanaan()
    {
        return $this->belongsTo(Perencanaan::class);
    }
}
