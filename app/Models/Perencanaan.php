<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Perencanaan",
 *     type="object",
 *     title="Perencanaan Model",
 *     description="Corporate Social Responsibility Planning Model",
 *     required={"nama_perusahaan","nama_pic","narahubung","jenis_kegiatan","lokasi","jumlah_bibit","jenis_bibit","tanggal_pelaksanaan","lat","long"},
 *     @OA\Property(property="id", type="integer", example=1, description="Unique identifier"),
 *     @OA\Property(property="user_id", type="integer", example=5, description="User who created this plan"),
 *     @OA\Property(property="nama_perusahaan", type="string", example="PT Hijau Nusantara", maxLength=255),
 *     @OA\Property(property="nama_pic", type="string", example="Budi Santoso", maxLength=255),
 *     @OA\Property(property="narahubung", type="string", example="+6281234567890", maxLength=20),
 *     @OA\Property(property="jenis_kegiatan", type="string", enum={"Planting Mangrove", "Coral Transplanting"}),
 *     @OA\Property(property="lokasi", type="string", example="Pantai Indah Kapuk, Jakarta Utara", maxLength=500),
 *     @OA\Property(property="jumlah_bibit", type="integer", example=1000, minimum=1, maximum=1000000),
 *     @OA\Property(property="jenis_bibit", type="string", example="Rhizophora apiculata", maxLength=255),
 *     @OA\Property(property="tanggal_pelaksanaan", type="string", format="date", example="2025-12-25"),
 *     @OA\Property(property="lat", type="number", format="float", example=-6.117664, minimum=-90, maximum=90),
 *     @OA\Property(property="long", type="number", format="float", example=106.899719, minimum=-180, maximum=180),
 *     @OA\Property(property="blockchain_document_id", type="string", nullable=true),
 *     @OA\Property(property="blockchain_tx_hash", type="string", nullable=true),
 *     @OA\Property(property="ipfs_cid", type="string", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Perencanaan extends Model
{
    use HasFactory, SoftDeletes;

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
        'blockchain_document_id',
        'blockchain_tx_hash',
        'ipfs_cid',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
        'jumlah_bibit' => 'integer',
        'lat' => 'decimal:8',
        'long' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'blockchain_document_id',
        'blockchain_tx_hash',
        'deleted_at',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function implementasi(): HasOne
    {
        return $this->hasOne(Implementasi::class);
    }

    // Scopes
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByJenisKegiatan($query, string $jenis)
    {
        return $query->where('jenis_kegiatan', $jenis);
    }

    // Accessors & Mutators
    public function getStatusAttribute(): string
    {
        if ($this->relationLoaded('implementasi') && $this->implementasi) {
            if ($this->implementasi->relationLoaded('monitoring') && $this->implementasi->monitoring) {
                return 'completed';
            }
            return 'implementation';
        }
        return 'planning';
    }

    public function getCoordinatesAttribute(): array
    {
        return [
            'latitude' => (float) $this->lat,
            'longitude' => (float) $this->long,
        ];
    }
}
