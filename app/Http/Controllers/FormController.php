<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perencanaan;
use App\Models\Implementasi;
use App\Http\Resources\FormResource;
use Validator;

class FormController extends Controller
{
    public function createPerencanaan(Request $request)
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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $perencanaan = Perencanaan::create([
            'user_id' => auth()->id(),
            ...$validator->validated()
        ]);

        return response()->json([
            'message' => 'Perencanaan created successfully',
            'data' => new FormResource($perencanaan)
        ], 201);
    }

    public function createImplementasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perencanaan_id' => 'required|exists:perencanaans,id',
            'nama_perusahaan_sesuai' => 'required|boolean',
            'lokasi_sesuai' => 'required|boolean',
            'jenis_kegiatan_sesuai' => 'required|boolean',
            'jumlah_bibit_sesuai' => 'required|boolean',
            'jenis_bibit_sesuai' => 'required|boolean',
            'tanggal_sesuai' => 'required|boolean',
            'pic_koorlap' => 'required|string',
            'dokumentasi_kegiatan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'geotagging' => 'required|string', // JSON string for coordinates
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();

        // Handle file upload
        $dokumentasiPath = $request->file('dokumentasi_kegiatan')->store('dokumentasi', 'public');

        $implementasi = Implementasi::create([
            'perencanaan_id' => $data['perencanaan_id'],
            'nama_perusahaan_sesuai' => $data['nama_perusahaan_sesuai'],
            'lokasi_sesuai' => $data['lokasi_sesuai'],
            'jenis_kegiatan_sesuai' => $data['jenis_kegiatan_sesuai'],
            'jumlah_bibit_sesuai' => $data['jumlah_bibit_sesuai'],
            'jenis_bibit_sesuai' => $data['jenis_bibit_sesuai'],
            'tanggal_sesuai' => $data['tanggal_sesuai'],
            'pic_koorlap' => $data['pic_koorlap'],
            'dokumentasi_kegiatan_path' => $dokumentasiPath,
            'geotagging_path' => $data['geotagging'],
        ]);

        return response()->json([
            'message' => 'Implementasi created successfully',
            'data' => new FormResource($implementasi)
        ], 201);
    }
}
