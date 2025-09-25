<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
{
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
            'daun_mengering' => 'required|in:<25%,25–45%,50–74%,>75%',
            'daun_layu' => 'required|in:<25%,25–45%,50–74%,>75%',
            'daun_menguning' => 'required|in:<25%,25–45%,50–74%,>75%',
            'bercak_daun' => 'required|in:<25%,25–45%,50–74%,>75%',
            'daun_serangga' => 'required|in:<25%,25–45%,50–74%,>75%',
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
}