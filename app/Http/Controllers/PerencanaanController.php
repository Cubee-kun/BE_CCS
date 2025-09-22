<?php

namespace App\Http\Controllers;

use App\Models\Perencanaan;
use Illuminate\Http\Request;

class PerencanaanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/perencanaan",
     *     tags={"Perencanaan"},
     *     summary="Ambil semua data perencanaan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List perencanaan",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Perencanaan"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        return response()->json(Perencanaan::all());
    }

    /**
     * @OA\Post(
     *     path="/api/perencanaan",
     *     tags={"Perencanaan"},
     *     summary="Tambah data perencanaan baru",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Perencanaan")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Perencanaan berhasil dibuat",
     *         @OA\JsonContent(ref="#/components/schemas/Perencanaan")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_perusahaan' => 'required|string',
            'nama_pic' => 'required|string',
            'narahubung' => 'required|string',
            'jenis_kegiatan' => 'required|string',
            'lokasi' => 'required|string',
            'jumlah_bibit' => 'required|integer',
            'jenis_bibit' => 'required|string',
            'tanggal_pelaksanaan' => 'required|date',
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);

        $perencanaan = Perencanaan::create($validated);

        return response()->json($perencanaan, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/perencanaan/{id}",
     *     tags={"Perencanaan"},
     *     summary="Ambil detail perencanaan berdasarkan ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID perencanaan",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail perencanaan",
     *         @OA\JsonContent(ref="#/components/schemas/Perencanaan")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Data tidak ditemukan")
     * )
     */
    public function show($id)
    {
        $perencanaan = Perencanaan::findOrFail($id);
        return response()->json($perencanaan);
    }

    /**
     * @OA\Put(
     *     path="/api/perencanaan/{id}",
     *     tags={"Perencanaan"},
     *     summary="Update perencanaan berdasarkan ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID perencanaan",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Perencanaan")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perencanaan berhasil diperbarui",
     *         @OA\JsonContent(ref="#/components/schemas/Perencanaan")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Data tidak ditemukan"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        $perencanaan = Perencanaan::findOrFail($id);

        $validated = $request->validate([
            'nama_perusahaan' => 'sometimes|string',
            'nama_pic' => 'sometimes|string',
            'narahubung' => 'sometimes|string',
            'jenis_kegiatan' => 'sometimes|string',
            'lokasi' => 'sometimes|string',
            'jumlah_bibit' => 'sometimes|integer',
            'jenis_bibit' => 'sometimes|string',
            'tanggal_pelaksanaan' => 'sometimes|date',
            'lat' => 'sometimes|string',
            'long' => 'sometimes|string',
        ]);

        $perencanaan->update($validated);

        return response()->json($perencanaan);
    }

    /**
     * @OA\Delete(
     *     path="/api/perencanaan/{id}",
     *     tags={"Perencanaan"},
     *     summary="Hapus perencanaan berdasarkan ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID perencanaan",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Perencanaan berhasil dihapus"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Data tidak ditemukan")
     * )
     */
    public function destroy($id)
    {
        $perencanaan = Perencanaan::findOrFail($id);
        $perencanaan->delete();

        return response()->json(['message' => 'Perencanaan deleted']);
    }
}
