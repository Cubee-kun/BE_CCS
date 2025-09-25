<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perencanaan;
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
     *             type="array",
     *             @OA\Items(type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $laporan = Perencanaan::with(['implementasi.monitoring'])->get();

        return response()->json($laporan);
    }

    /**
     * @OA\Get(
     *     path="/api/laporan/{id}",
     *     tags={"Laporan"},
     *     summary="Get laporan detail by perencanaan ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Laporan detail",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $perencanaan = Perencanaan::with(['implementasi.monitoring'])->findOrFail($id);
        return response()->json($perencanaan);
    }

    /**
     * @OA\Get(
     *     path="/api/laporan/cetak",
     *     tags={"Laporan"},
     *     summary="Download seluruh laporan sebagai PDF",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="PDF file",
     *         content={
     *           @OA\MediaType(
     *             mediaType="application/pdf",
     *             @OA\Schema(type="string", format="binary")
     *           )
     *         }
     *     )
     * )
     */
    public function cetak()
    {
        $laporan = Perencanaan::with(['implementasi.monitoring'])->get();
        $pdf = Pdf::loadView('laporan.pdf', ['laporan' => $laporan])->setPaper('a4', 'portrait');
        return $pdf->download('laporan-semua.pdf');
    }

    /**
     * @OA\Get(
     *     path="/api/laporan/cetak/{id}",
     *     tags={"Laporan"},
     *     summary="Download laporan perencanaan tertentu sebagai PDF",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="PDF file",
     *         content={
     *           @OA\MediaType(
     *             mediaType="application/pdf",
     *             @OA\Schema(type="string", format="binary")
     *           )
     *         }
     *     ),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function cetakById($id)
    {
        $perencanaan = Perencanaan::with(['implementasi.monitoring'])->findOrFail($id);
        $pdf = Pdf::loadView('laporan.pdf', ['laporan' => collect([$perencanaan])])->setPaper('a4', 'portrait');
        return $pdf->download("laporan-perencanaan-{$id}.pdf");
    }
}