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
     *     summary="Get all perencanaan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of perencanaan",
     *         @OA\JsonContent(type="array", @OA\Items())
     *     )
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
     *     summary="Create perencanaan",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","nama_perusahaan","nama_pic","narahubung","jenis_kegiatan","lokasi","jumlah_bibit","jenis_bibit","tanggal_pelaksanaan","lat","long"},
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="nama_perusahaan", type="string"),
     *             @OA\Property(property="nama_pic", type="string"),
     *             @OA\Property(property="narahubung", type="string"),
     *             @OA\Property(property="jenis_kegiatan", type="string"),
     *             @OA\Property(property="lokasi", type="string"),
     *             @OA\Property(property="jumlah_bibit", type="integer"),
     *             @OA\Property(property="jenis_bibit", type="string"),
     *             @OA\Property(property="tanggal_pelaksanaan", type="string", format="date"),
     *             @OA\Property(property="lat", type="string"),
     *             @OA\Property(property="long", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Perencanaan created")
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
     *     summary="Get perencanaan by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Perencanaan detail")
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
     *     summary="Update perencanaan by ID",
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
     *             @OA\Property(property="nama_perusahaan", type="string"),
     *             @OA\Property(property="nama_pic", type="string"),
     *             @OA\Property(property="narahubung", type="string"),
     *             @OA\Property(property="jenis_kegiatan", type="string"),
     *             @OA\Property(property="lokasi", type="string"),
     *             @OA\Property(property="jumlah_bibit", type="integer"),
     *             @OA\Property(property="jenis_bibit", type="string"),
     *             @OA\Property(property="tanggal_pelaksanaan", type="string", format="date"),
     *             @OA\Property(property="lat", type="string"),
     *             @OA\Property(property="long", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Perencanaan updated")
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
     *     summary="Delete perencanaan by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Perencanaan deleted")
     * )
     */
    public function destroy($id)
    {
        $perencanaan = Perencanaan::findOrFail($id);
        $perencanaan->delete();

        return response()->json(['message' => 'Perencanaan deleted']);
    }
}