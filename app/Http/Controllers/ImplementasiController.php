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
    *   path="/api/implementasi",
    *   tags={"Implementasi"},
    *   summary="Create implementasi",
    *   security={{"bearerAuth":{}}},
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perencanaan_id' => 'required|exists:perencanaans,id',
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
            'perencanaan_id' => $request->perencanaan_id,
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

    /**
     * @OA\Get(
     *   path="/api/implementasi",
     *   tags={"Implementasi"},
     *   summary="Get all implementasi",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Success")
     * )
     */
    public function index()
    {
        $implementasi = Implementasi::with('perencanaan')->get();
        return response()->json($implementasi);
    }

    /**
     * @OA\Get(
     *   path="/api/implementasi/{id}",
     *   tags={"Implementasi"},
     *   summary="Get implementasi by ID",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $implementasi = Implementasi::with('perencanaan')->findOrFail($id);
        return response()->json($implementasi);
    }

    /**
     * @OA\Put(
     *   path="/api/implementasi/{id}",
     *   tags={"Implementasi"},
     *   summary="Update implementasi",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/Implementasi")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Implementasi updated"),
     *   @OA\Response(response=404, description="Not found"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        $implementasi = Implementasi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_perusahaan_sesuai' => 'sometimes|required|boolean',
            'lokasi_sesuai' => 'sometimes|required|boolean',
            'jenis_kegiatan_sesuai' => 'sometimes|required|boolean',
            'jumlah_bibit_sesuai' => 'sometimes|required|boolean',
            'jenis_bibit_sesuai' => 'sometimes|required|boolean',
            'tanggal_sesuai' => 'sometimes|required|boolean',
            'pic_koorlap' => 'sometimes|required|string',
            'dokumentasi_kegiatan' => 'nullable|file|image|max:2048',
            'geotagging' => 'nullable|string',
            'lat' => 'sometimes|required|string',
            'long' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('dokumentasi_kegiatan')) {
            if ($implementasi->dokumentasi_kegiatan) {
                Storage::disk('public')->delete($implementasi->dokumentasi_kegiatan);
            }
            $dokPath = $request->file('dokumentasi_kegiatan')->store('dokumentasi', 'public');
            $implementasi->dokumentasi_kegiatan = $dokPath;
        }

        $implementasi->fill($request->except('dokumentasi_kegiatan'));
        $implementasi->save();

        return response()->json([
            'message' => 'Implementasi updated successfully',
            'implementasi' => $implementasi
        ]);
    }

    /**
     * @OA\Delete(
     *   path="/api/implementasi/{id}",
     *   tags={"Implementasi"},
     *   summary="Delete implementasi",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Implementasi deleted"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy($id)
    {
        $implementasi = Implementasi::findOrFail($id);

        if ($implementasi->dokumentasi_kegiatan) {
            Storage::disk('public')->delete($implementasi->dokumentasi_kegiatan);
        }

        $implementasi->delete();

        return response()->json(['message' => 'Implementasi deleted successfully']);
    }
}
