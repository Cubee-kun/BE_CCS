<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'implementasi_id',
        'survival_rate',
        'tinggi_bibit_rata',
        'diameter_batang_rata',
        'kondisi_kesehatan',
    ];

    public function implementasi()
    {
        return $this->belongsTo(Implementasi::class);
    }
}