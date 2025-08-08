<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $fillable = [
        'implementasi_id', 'jumlah_bibit_ditanam', 'jumlah_bibit_mati',
        'diameter_batang', 'jumlah_daun', 'survival_rate',
        'dokumentasi_monitoring_path'
    ];

    protected $casts = [
        'survival_rate' => 'array'
    ];

    public function implementasi()
    {
        return $this->belongsTo(Implementasi::class);
    }
}
