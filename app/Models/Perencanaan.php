<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Perencanaan",
 *     type="object",
 *     title="Perencanaan",
 *     description="Schema untuk data Perencanaan",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=5),
 *     @OA\Property(property="nama_perusahaan", type="string", example="PT Pertamina"),
 *     @OA\Property(property="nama_pic", type="string", example="Budi Santoso"),
 *     @OA\Property(property="narahubung", type="string", example="08123456789"),
 *     @OA\Property(property="jenis_kegiatan", type="string", example="Penanaman Pohon"),
 *     @OA\Property(property="lokasi", type="string", example="Jakarta Selatan"),
 *     @OA\Property(property="jumlah_bibit", type="integer", example=1000),
 *     @OA\Property(property="jenis_bibit", type="string", example="Mangrove"),
 *     @OA\Property(property="tanggal_pelaksanaan", type="string", format="date", example="2025-09-20"),
 *     @OA\Property(property="lat", type="string", example="-6.200000"),
 *     @OA\Property(property="long", type="string", example="106.816666"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-16T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-16T10:00:00Z")
 * )
 */
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

    public function implementasi()
    {
        return $this->hasOne(Implementasi::class);
    }
}
