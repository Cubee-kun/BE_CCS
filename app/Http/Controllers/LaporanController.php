<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perencanaan;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Perencanaan::with(['implementasi.monitoring'])->get();
        return response()->json($laporan);
    }

    public function show($id)
    {
        $perencanaan = Perencanaan::with(['implementasi.monitoring'])->findOrFail($id);
        return response()->json($perencanaan);
    }

    public function cetak()
    {
        $laporan = Perencanaan::with(['implementasi.monitoring'])->get();
        $pdf = Pdf::loadView('laporan.pdf', ['laporan' => $laporan])->setPaper('a4', 'portrait');
        return $pdf->download('laporan-semua.pdf');
    }

    public function cetakById($id)
    {
        $perencanaan = Perencanaan::with(['implementasi.monitoring'])->findOrFail($id);
        $pdf = Pdf::loadView('laporan.pdf', ['laporan' => collect([$perencanaan])])->setPaper('a4', 'portrait');
        return $pdf->download("laporan-perencanaan-{$id}.pdf");
    }

    // Tambahan CRUD manual

    public function store(Request $request)
    {
        $laporan = Perencanaan::create($request->all());
        return response()->json([
            'message' => 'Laporan created',
            'data' => $laporan
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $laporan = Perencanaan::findOrFail($id);
        $laporan->update($request->all());

        return response()->json([
            'message' => 'Laporan updated',
            'data' => $laporan
        ]);
    }

    public function destroy($id)
    {
        $laporan = Perencanaan::findOrFail($id);
        $laporan->delete();

        return response()->json(['message' => 'Laporan deleted']);
    }
}
