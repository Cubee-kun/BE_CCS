<?php

namespace App\Http\Controllers;

use App\Models\Evaluasi;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/evaluasi",
     *     tags={"Evaluasi"},
     *     summary="Get all evaluasi",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of evaluasi",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Evaluasi"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Evaluasi::all());
    }

    /**
     * @OA\Post(
     *     path="/api/evaluasi",
     *     tags={"Evaluasi"},
     *     summary="Create new evaluasi",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"implementasi_id","survival_rate","tinggi_bibit_rata","diameter_batang_rata","kondisi_kesehatan"},
     *             @OA\Property(property="implementasi_id", type="integer"),
     *             @OA\Property(property="survival_rate", type="number", format="float"),
     *             @OA\Property(property="tinggi_bibit_rata", type="number", format="float"),
     *             @OA\Property(property="diameter_batang_rata", type="number", format="float"),
     *             @OA\Property(property="kondisi_kesehatan", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Evaluasi created",
     *         @OA\JsonContent(ref="#/components/schemas/Evaluasi")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'implementasi_id' => 'required|exists:implementasis,id',
            'survival_rate' => 'required|numeric|between:0,100',
            'tinggi_bibit_rata' => 'required|numeric|min:0',
            'diameter_batang_rata' => 'required|numeric|min:0',
            'kondisi_kesehatan' => 'required|string|max:255',
        ]);

        $evaluasi = Evaluasi::create($validated);

        return response()->json($evaluasi, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/evaluasi/{id}",
     *     tags={"Evaluasi"},
     *     summary="Get evaluasi by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evaluasi detail",
     *         @OA\JsonContent(ref="#/components/schemas/Evaluasi")
     *     )
     * )
     */
    public function show(Evaluasi $evaluasi)
    {
        return response()->json($evaluasi);
    }

    /**
     * @OA\Put(
     *     path="/api/evaluasi/{id}",
     *     tags={"Evaluasi"},
     *     summary="Update evaluasi by ID",
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
     *             @OA\Property(property="survival_rate", type="number", format="float"),
     *             @OA\Property(property="tinggi_bibit_rata", type="number", format="float"),
     *             @OA\Property(property="diameter_batang_rata", type="number", format="float"),
     *             @OA\Property(property="kondisi_kesehatan", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evaluasi updated",
     *         @OA\JsonContent(ref="#/components/schemas/Evaluasi")
     *     )
     * )
     */
    public function update(Request $request, Evaluasi $evaluasi)
    {
        $validated = $request->validate([
            'survival_rate' => 'sometimes|numeric|between:0,100',
            'tinggi_bibit_rata' => 'sometimes|numeric|min:0',
            'diameter_batang_rata' => 'sometimes|numeric|min:0',
            'kondisi_kesehatan' => 'sometimes|string|max:255',
        ]);

        $evaluasi->update($validated);

        return response()->json($evaluasi);
    }

    /**
     * @OA\Delete(
     *     path="/api/evaluasi/{id}",
     *     tags={"Evaluasi"},
     *     summary="Delete evaluasi by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evaluasi deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(Evaluasi $evaluasi)
    {
        $evaluasi->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

/**
 * @OA\Schema(
 *     schema="Evaluasi",
 *     type="object",
 *     required={"implementasi_id","survival_rate","tinggi_bibit_rata","diameter_batang_rata","kondisi_kesehatan"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="implementasi_id", type="integer"),
 *     @OA\Property(property="survival_rate", type="number", format="float"),
 *     @OA\Property(property="tinggi_bibit_rata", type="number", format="float"),
 *     @OA\Property(property="diameter_batang_rata", type="number", format="float"),
 *     @OA\Property(property="kondisi_kesehatan", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */