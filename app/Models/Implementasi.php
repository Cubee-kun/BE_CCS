<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Implementasi extends Model
{
    protected $fillable = [
        'perencanaan_id', 'nama_perusahaan_sesuai', 'lokasi_sesuai',
        'jenis_kegiatan_sesuai', 'jumlah_bibit_sesuai', 'jenis_bibit_sesuai',
        'tanggal_sesuai', 'pic_koorlap', 'dokumentasi_kegiatan_path',
        'geotagging_path'
    ];

    public function perencanaan()
    {
        return $this->belongsTo(Perencanaan::class);
    }

    public function monitoring()
    {
        return $this->hasOne(Monitoring::class);
    }
}
