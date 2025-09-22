<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Evaluasi",
 *     type="object",
 *     required={"implementasi_id","survival_rate","tinggi_bibit_rata","diameter_batang_rata","kondisi_kesehatan"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="implementasi_id", type="integer"),
 *     @OA\Property(property="survival_rate", type="number", format="float"),
 *     @OA\Property(property="tinggi_bibit_rata", type="number", format="float"),
 *     @OA\Property(property="diameter_batang_rata", type="number", format="float"),
 *     @OA\Property(property="kondisi_kesehatan", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
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
