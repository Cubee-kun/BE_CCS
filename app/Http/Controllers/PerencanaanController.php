<?php

namespace App\Http\Controllers;

use App\Models\Perencanaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PerencanaanController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/perencanaan",
     *   tags={"Perencanaan"},
     *   summary="Get all perencanaan (current user)",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Success")
     * )
     */
    public function index()
    {
        $perencanaan = Perencanaan::where('user_id', auth()->id())->get();
        return response()->json($perencanaan);
    }

    /**
     * @OA\Post(
     *   path="/api/perencanaan",
     *   tags={"Perencanaan"},
     *   summary="Create perencanaan",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Perencanaan")
     *   ),
     *   @OA\Response(response=201, description="Perencanaan created"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string',
            'nama_pic' => 'required|string',
            'narahubung' => 'required|string',
            'jenis_kegiatan' => 'required|in:Planting Mangrove,Coral Transplanting',
            'lokasi' => 'required|string',
            'jumlah_bibit' => 'required|integer',
            'jenis_bibit' => 'required|string',
            'tanggal_pelaksanaan' => 'required|date',
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $perencanaan = Perencanaan::create([
            'user_id' => auth()->id(),
            'nama_perusahaan' => $request->nama_perusahaan,
            'nama_pic' => $request->nama_pic,
            'narahubung' => $request->narahubung,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'lokasi' => $request->lokasi,
            'jumlah_bibit' => $request->jumlah_bibit,
            'jenis_bibit' => $request->jenis_bibit,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'lat' => $request->lat,
            'long' => $request->long,
        ]);

        return response()->json([
            'message' => 'Perencanaan created successfully',
            'perencanaan' => $perencanaan
        ], 201);
    }

    /**
     * @OA\Get(
     *   path="/api/perencanaan/{id}",
     *   tags={"Perencanaan"},
     *   summary="Get perencanaan by ID",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $perencanaan = Perencanaan::where('user_id', auth()->id())->findOrFail($id);
        return response()->json($perencanaan);
    }

    /**
     * @OA\Put(
     *   path="/api/perencanaan/{id}",
     *   tags={"Perencanaan"},
     *   summary="Update perencanaan",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Perencanaan")
     *   ),
     *   @OA\Response(response=200, description="Updated successfully"),
     *   @OA\Response(response=404, description="Not found"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        $perencanaan = Perencanaan::where('user_id', auth()->id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'sometimes|required|string',
            'nama_pic' => 'sometimes|required|string',
            'narahubung' => 'sometimes|required|string',
            'jenis_kegiatan' => 'sometimes|required|in:Planting Mangrove,Coral Transplanting',
            'lokasi' => 'sometimes|required|string',
            'jumlah_bibit' => 'sometimes|required|integer',
            'jenis_bibit' => 'sometimes|required|string',
            'tanggal_pelaksanaan' => 'sometimes|required|date',
            'lat' => 'sometimes|required|string',
            'long' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $perencanaan->update($request->all());

        return response()->json([
            'message' => 'Perencanaan updated successfully',
            'perencanaan' => $perencanaan
        ]);
    }

    /**
     * @OA\Delete(
     *   path="/api/perencanaan/{id}",
     *   tags={"Perencanaan"},
     *   summary="Delete perencanaan",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=200, description="Deleted successfully"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy($id)
    {
        $perencanaan = Perencanaan::where('user_id', auth()->id())->findOrFail($id);
        $perencanaan->delete();

        return response()->json([
            'message' => 'Perencanaan deleted successfully'
        ]);
    }
}
