<?php

namespace App\Http\Controllers;

use App\Models\Implementasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ImplementasiController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/implementasi/{perencanaan_id}",
     *   tags={"Implementasi"},
     *   summary="Create implementasi",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="perencanaan_id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/Implementasi")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Implementasi created"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request, $perencanaan_id)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan_sesuai' => 'required|boolean',
            'lokasi_sesuai' => 'required|boolean',
            'jenis_kegiatan_sesuai' => 'required|boolean',
            'jumlah_bibit_sesuai' => 'required|boolean',
            'jenis_bibit_sesuai' => 'required|boolean',
            'tanggal_sesuai' => 'required|boolean',
            'pic_koorlap' => 'required|string',
            'dokumentasi_kegiatan' => 'nullable|file|image|max:2048',
            'geotagging' => 'nullable|string',
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $dokPath = null;
        if ($request->hasFile('dokumentasi_kegiatan')) {
            $dokPath = $request->file('dokumentasi_kegiatan')->store('dokumentasi', 'public');
        }

        $implementasi = Implementasi::create([
            'perencanaan_id' => $perencanaan_id,
            'nama_perusahaan_sesuai' => $request->nama_perusahaan_sesuai,
            'lokasi_sesuai' => $request->lokasi_sesuai,
            'jenis_kegiatan_sesuai' => $request->jenis_kegiatan_sesuai,
            'jumlah_bibit_sesuai' => $request->jumlah_bibit_sesuai,
            'jenis_bibit_sesuai' => $request->jenis_bibit_sesuai,
            'tanggal_sesuai' => $request->tanggal_sesuai,
            'pic_koorlap' => $request->pic_koorlap,
            'dokumentasi_kegiatan' => $dokPath,
            'geotagging' => $request->geotagging,
            'lat' => $request->lat,
            'long' => $request->long,
        ]);

        return response()->json([
            'message' => 'Implementasi created successfully',
            'implementasi' => $implementasi
        ], 201);
    }
}