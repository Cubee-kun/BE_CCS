<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerencanaanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_perusahaan' => $this->nama_perusahaan,
            'nama_pic' => $this->nama_pic,
            'narahubung' => $this->narahubung,
            'jenis_kegiatan' => $this->jenis_kegiatan,
            'lokasi' => $this->lokasi,
            'jumlah_bibit' => $this->jumlah_bibit,
            'jenis_bibit' => $this->jenis_bibit,
            'tanggal_pelaksanaan' => $this->tanggal_pelaksanaan->format('Y-m-d'),
            'coordinates' => [
                'latitude' => (float) $this->lat,
                'longitude' => (float) $this->long,
            ],
            'status' => $this->getStatusAttribute(),
            'blockchain' => $this->when($this->blockchain_document_id, [
                'document_id' => $this->blockchain_document_id,
                'tx_hash' => $this->blockchain_tx_hash,
                'ipfs_cid' => $this->ipfs_cid,
            ]),
            'implementasi' => ImplementasiResource::make($this->whenLoaded('implementasi')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }

    private function getStatusAttribute(): string
    {
        if ($this->relationLoaded('implementasi') && $this->implementasi) {
            if ($this->implementasi->relationLoaded('monitoring') && $this->implementasi->monitoring) {
                return 'completed';
            }
            return 'implementation';
        }
        return 'planning';
    }
}
