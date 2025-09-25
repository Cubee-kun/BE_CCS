<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/monitoring",
     *   tags={"Monitoring"},
     *   summary="Get all monitoring data",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Success")
     * )
     */
    public function index()
    {
        $monitoring = Monitoring::with('implementasi')->get();
        return response()->json($monitoring);
    }

    /**
     * @OA\Post(
     *   path="/api/monitoring/{implementasi_id}",
     *   tags={"Monitoring"},
     *   summary="Create monitoring",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="implementasi_id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/Monitoring")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Monitoring created"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request, $implementasi_id)
    {
        $validator = Validator::make($request->all(), [
            'jumlah_bibit_ditanam' => 'required|integer',
            'jumlah_bibit_mati' => 'required|integer',
            'diameter_batang' => 'required|numeric',
            'jumlah_daun' => 'required|integer',
            'daun_mengering' => 'required|string',
            'daun_layu' => 'required|string',
            'daun_menguning' => 'required|string',
            'bercak_daun' => 'required|string',
            'daun_serangga' => 'required|string',
            'survival_rate' => 'required|numeric|between:0,100',
            'dokumentasi_monitoring' => 'nullable|file|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $dokPath = null;
        if ($request->hasFile('dokumentasi_monitoring')) {
            $dokPath = $request->file('dokumentasi_monitoring')->store('monitoring', 'public');
        }

        $monitoring = Monitoring::create([
            'implementasi_id' => $implementasi_id,
            'jumlah_bibit_ditanam' => $request->jumlah_bibit_ditanam,
            'jumlah_bibit_mati' => $request->jumlah_bibit_mati,
            'diameter_batang' => $request->diameter_batang,
            'jumlah_daun' => $request->jumlah_daun,
            'daun_mengering' => $request->daun_mengering,
            'daun_layu' => $request->daun_layu,
            'daun_menguning' => $request->daun_menguning,
            'bercak_daun' => $request->bercak_daun,
            'daun_serangga' => $request->daun_serangga,
            'survival_rate' => $request->survival_rate,
            'dokumentasi_monitoring' => $dokPath,
        ]);

        return response()->json([
            'message' => 'Monitoring created successfully',
            'monitoring' => $monitoring
        ], 201);
    }

    /**
     * @OA\Get(
     *   path="/api/monitoring/{id}",
     *   tags={"Monitoring"},
     *   summary="Get monitoring by ID",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $monitoring = Monitoring::with('implementasi')->findOrFail($id);
        return response()->json($monitoring);
    }

    /**
     * @OA\Put(
     *   path="/api/monitoring/{id}",
     *   tags={"Monitoring"},
     *   summary="Update monitoring",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/Monitoring")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Updated successfully"),
     *   @OA\Response(response=404, description="Not found"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        $monitoring = Monitoring::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'jumlah_bibit_ditanam' => 'sometimes|required|integer',
            'jumlah_bibit_mati' => 'sometimes|required|integer',
            'diameter_batang' => 'sometimes|required|numeric',
            'jumlah_daun' => 'sometimes|required|integer',
            'daun_mengering' => 'sometimes|required|string',
            'daun_layu' => 'sometimes|required|string',
            'daun_menguning' => 'sometimes|required|string',
            'bercak_daun' => 'sometimes|required|string',
            'daun_serangga' => 'sometimes|required|string',
            'survival_rate' => 'sometimes|required|numeric|between:0,100',
            'dokumentasi_monitoring' => 'nullable|file|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('dokumentasi_monitoring')) {
            if ($monitoring->dokumentasi_monitoring) {
                Storage::disk('public')->delete($monitoring->dokumentasi_monitoring);
            }
            $monitoring->dokumentasi_monitoring = $request->file('dokumentasi_monitoring')->store('monitoring', 'public');
        }

        $monitoring->update($request->except('dokumentasi_monitoring'));

        return response()->json([
            'message' => 'Monitoring updated successfully',
            'monitoring' => $monitoring
        ]);
    }

    /**
     * @OA\Delete(
     *   path="/api/monitoring/{id}",
     *   tags={"Monitoring"},
     *   summary="Delete monitoring",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Deleted successfully"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy($id)
    {
        $monitoring = Monitoring::findOrFail($id);

        if ($monitoring->dokumentasi_monitoring) {
            Storage::disk('public')->delete($monitoring->dokumentasi_monitoring);
        }

        $monitoring->delete();

        return response()->json([
            'message' => 'Monitoring deleted successfully'
        ]);
    }
}
