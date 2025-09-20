<?php

namespace App\Http\Controllers;

use App\Models\Implementasi;
use Illuminate\Http\Request;

class ImplementasiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/implementasi",
     *     tags={"Implementasi"},
     *     summary="Get all implementasi",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of implementasi",
     *         @OA\JsonContent(type="array", @OA\Items())
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Implementasi::with('perencanaan')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/implementasi",
     *     tags={"Implementasi"},
     *     summary="Create implementasi",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"perencanaan_id","pic_koorlap","lat","long"},
     *             @OA\Property(property="perencanaan_id", type="integer"),
     *             @OA\Property(property="nama_perusahaan_sesuai", type="boolean"),
     *             @OA\Property(property="lokasi_sesuai", type="boolean"),
     *             @OA\Property(property="jenis_kegiatan_sesuai", type="boolean"),
     *             @OA\Property(property="jumlah_bibit_sesuai", type="boolean"),
     *             @OA\Property(property="jenis_bibit_sesuai", type="boolean"),
     *             @OA\Property(property="tanggal_sesuai", type="boolean"),
     *             @OA\Property(property="pic_koorlap", type="string"),
     *             @OA\Property(property="dokumentasi_kegiatan", type="string"),
     *             @OA\Property(property="geotagging", type="string"),
     *             @OA\Property(property="lat", type="string"),
     *             @OA\Property(property="long", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Implementasi created")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'perencanaan_id' => 'required|exists:perencanaans,id',
            'nama_perusahaan_sesuai' => 'required|boolean',
            'lokasi_sesuai' => 'required|boolean',
            'jenis_kegiatan_sesuai' => 'required|boolean',
            'jumlah_bibit_sesuai' => 'required|boolean',
            'jenis_bibit_sesuai' => 'required|boolean',
            'tanggal_sesuai' => 'required|boolean',
            'pic_koorlap' => 'required|string',
            'dokumentasi_kegiatan' => 'nullable|string',
            'geotagging' => 'nullable|string',
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);

        $implementasi = Implementasi::create($validated);

        return response()->json($implementasi, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/implementasi/{id}",
     *     tags={"Implementasi"},
     *     summary="Get implementasi by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Implementasi detail")
     * )
     */
    public function show($id)
    {
        $implementasi = Implementasi::with('perencanaan')->findOrFail($id);
        return response()->json($implementasi);
    }

    /**
     * @OA\Put(
     *     path="/api/implementasi/{id}",
     *     tags={"Implementasi"},
     *     summary="Update implementasi by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_perusahaan_sesuai", type="boolean"),
     *             @OA\Property(property="lokasi_sesuai", type="boolean"),
     *             @OA\Property(property="jenis_kegiatan_sesuai", type="boolean"),
     *             @OA\Property(property="jumlah_bibit_sesuai", type="boolean"),
     *             @OA\Property(property="jenis_bibit_sesuai", type="boolean"),
     *             @OA\Property(property="tanggal_sesuai", type="boolean"),
     *             @OA\Property(property="pic_koorlap", type="string"),
     *             @OA\Property(property="dokumentasi_kegiatan", type="string"),
     *             @OA\Property(property="geotagging", type="string"),
     *             @OA\Property(property="lat", type="string"),
     *             @OA\Property(property="long", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Implementasi updated")
     * )
     */
    public function update(Request $request, $id)
    {
        $implementasi = Implementasi::findOrFail($id);

        $validated = $request->validate([
            'nama_perusahaan_sesuai' => 'sometimes|boolean',
            'lokasi_sesuai' => 'sometimes|boolean',
            'jenis_kegiatan_sesuai' => 'sometimes|boolean',
            'jumlah_bibit_sesuai' => 'sometimes|boolean',
            'jenis_bibit_sesuai' => 'sometimes|boolean',
            'tanggal_sesuai' => 'sometimes|boolean',
            'pic_koorlap' => 'sometimes|string',
            'dokumentasi_kegiatan' => 'nullable|string',
            'geotagging' => 'nullable|string',
            'lat' => 'sometimes|string',
            'long' => 'sometimes|string',
        ]);

        $implementasi->update($validated);

        return response()->json($implementasi);
    }

    /**
     * @OA\Delete(
     *     path="/api/implementasi/{id}",
     *     tags={"Implementasi"},
     *     summary="Delete implementasi by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Implementasi deleted")
     * )
     */
    public function destroy($id)
    {
        $implementasi = Implementasi::findOrFail($id);
        $implementasi->delete();

        return response()->json(['message' => 'Implementasi deleted']);
    }
}