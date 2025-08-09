<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'implementasi_id',
        'jumlah_bibit_ditanam',
        'jumlah_bibit_mati',
        'diameter_batang',
        'jumlah_daun',
        'daun_mengering',
        'daun_layu',
        'daun_menguning',
        'bercak_daun',
        'daun_serangga',
        'survival_rate',
        'dokumentasi_monitoring',
    ];

    public function implementasi()
    {
        return $this->belongsTo(Implementasi::class);
    }
}
