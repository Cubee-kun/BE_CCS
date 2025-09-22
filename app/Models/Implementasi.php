<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Implementasi",
 *     type="object",
 *     required={"perencanaan_id","nama_perusahaan_sesuai","lokasi_sesuai","jenis_kegiatan_sesuai","jumlah_bibit_sesuai","jenis_bibit_sesuai","tanggal_sesuai","pic_koorlap"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="perencanaan_id", type="integer", example=5, description="ID dari perencanaan terkait"),
 *     @OA\Property(property="nama_perusahaan_sesuai", type="string", example="PT Hijau Lestari"),
 *     @OA\Property(property="lokasi_sesuai", type="string", example="Kecamatan Sukamaju, Kabupaten Bandung"),
 *     @OA\Property(property="jenis_kegiatan_sesuai", type="string", example="Penanaman Pohon"),
 *     @OA\Property(property="jumlah_bibit_sesuai", type="integer", example=1000),
 *     @OA\Property(property="jenis_bibit_sesuai", type="string", example="Mahoni"),
 *     @OA\Property(property="tanggal_sesuai", type="string", format="date", example="2025-09-16"),
 *     @OA\Property(property="pic_koorlap", type="string", example="Budi Santoso"),
 *     @OA\Property(property="dokumentasi_kegiatan", type="string", format="binary", example="dokumen.jpg"),
 *     @OA\Property(property="geotagging", type="string", example="https://maps.google.com/?q=-6.917464,107.619123"),
 *     @OA\Property(property="lat", type="number", format="float", example=-6.917464),
 *     @OA\Property(property="long", type="number", format="float", example=107.619123),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-16T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-16T12:34:56Z")
 * )
 */
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
        'lat',
        'long',
    ];

    public function perencanaan()
    {
        return $this->belongsTo(Perencanaan::class);
    }

    // Tambahkan di app/Models/Implementasi.php
    public function monitoring()
    {
        return $this->hasOne(Monitoring::class);
    }
}
