<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perencanaan;
use App\Models\Implementasi;
use App\Models\Monitoring;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/laporan",
     *     tags={"Laporan"},
     *     summary="Get all laporan (perencanaan, implementasi, monitoring)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List laporan",
     *         @OA\JsonContent(
     *             @OA\Property(property="laporan", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Ambil semua data perencanaan beserta relasi implementasi dan monitoring
        $laporan = Perencanaan::with(['implementasi.monitoring'])->get();

        return response()->json([
            'laporan' => $laporan
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/laporan/{id}",
     *     tags={"Laporan"},
     *     summary="Get laporan detail by perencanaan ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Laporan detail",
     *         @OA\JsonContent(
     *             @OA\Property(property="laporan", type="object")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $perencanaan = Perencanaan::with(['implementasi.monitoring'])->findOrFail($id);

        return response()->json([
            'laporan' => $perencanaan
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/laporan/cetak",
     *     tags={"Laporan"},
     *     summary="Download laporan as PDF",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="PDF file"
     *     )
     * )
     */
    public function cetak()
    {
        $laporan = Perencanaan::with(['implementasi.monitoring'])->get();

        $pdf = Pdf::loadView('laporan.pdf', ['laporan' => $laporan]);
        return $pdf->download('laporan.pdf');
    }
}