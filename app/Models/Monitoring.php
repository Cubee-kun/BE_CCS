<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Monitoring",
 *     type="object",
 *     required={"implementasi_id","jumlah_bibit_ditanam","jumlah_bibit_mati","diameter_batang","jumlah_daun"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="implementasi_id", type="integer", example=10, description="ID implementasi terkait"),
 *     @OA\Property(property="jumlah_bibit_ditanam", type="integer", example=950, description="Jumlah bibit yang berhasil ditanam"),
 *     @OA\Property(property="jumlah_bibit_mati", type="integer", example=50, description="Jumlah bibit yang mati"),
 *     @OA\Property(property="diameter_batang", type="number", format="float", example=2.5, description="Diameter batang dalam cm"),
 *     @OA\Property(property="jumlah_daun", type="integer", example=200, description="Jumlah rata-rata daun"),
 *     @OA\Property(property="daun_mengering", type="integer", example=5, description="Jumlah bibit dengan daun mengering"),
 *     @OA\Property(property="daun_layu", type="integer", example=3, description="Jumlah bibit dengan daun layu"),
 *     @OA\Property(property="daun_menguning", type="integer", example=2, description="Jumlah bibit dengan daun menguning"),
 *     @OA\Property(property="bercak_daun", type="integer", example=1, description="Jumlah bibit dengan bercak pada daun"),
 *     @OA\Property(property="daun_serangga", type="integer", example=4, description="Jumlah bibit yang terserang serangga"),
 *     @OA\Property(property="survival_rate", type="number", format="float", example=95.0, description="Persentase tingkat kelangsungan hidup bibit"),
 *     @OA\Property(property="dokumentasi_monitoring", type="string", format="binary", example="monitoring.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-16T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-16T12:34:56Z")
 * )
 */
class Monitoring extends Model
{
    use HasFactory;

    protected 
    $fillable = [
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
