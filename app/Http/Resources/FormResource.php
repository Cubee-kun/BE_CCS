<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama_perusahaan' => $this->nama_perusahaan ?? $this->perencanaan->nama_perusahaan,
            'jenis_kegiatan' => $this->jenis_kegiatan ?? $this->perencanaan->jenis_kegiatan,
            'lokasi' => $this->lokasi ?? $this->perencanaan->lokasi,
            'tanggal_pelaksanaan' => $this->tanggal_pelaksanaan ?? $this->perencanaan->tanggal_pelaksanaan,
            'status' => $this->determineStatus(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'details' => $this->whenLoaded('implementasi', function() {
                return [
                    'implementasi' => [
                        'pic_koorlap' => $this->implementasi->pic_koorlap,
                        'dokumentasi' => asset('storage/'.$this->implementasi->dokumentasi_kegiatan_path),
                        'geotagging' => $this->implementasi->geotagging_path,
                    ],
                    'monitoring' => $this->whenLoaded('implementasi.monitoring', function() {
                        return [
                            'survival_rate' => json_decode($this->implementasi->monitoring->survival_rate, true),
                            'dokumentasi' => asset('storage/'.$this->implementasi->monitoring->dokumentasi_monitoring_path),
                        ];
                    })
                ];
            })
        ];
    }

    protected function determineStatus()
    {
        if ($this->implementasi && $this->implementasi->monitoring) {
            return 'completed';
        } elseif ($this->implementasi) {
            return 'implemented';
        } else {
            return 'planned';
        }
    }
}
