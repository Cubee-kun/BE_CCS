<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monitoring;
use App\Http\Resources\FormResource;
use Validator;

class MonitoringController extends Controller
{
    public function createMonitoring(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'implementasi_id' => 'required|exists:implementasis,id',
            'jumlah_bibit_ditanam' => 'required|integer',
            'jumlah_bibit_mati' => 'required|integer',
            'diameter_batang' => 'required|numeric',
            'jumlah_daun' => 'required|integer',
            'survival_rate' => 'required|array',
            'dokumentasi_monitoring' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();

        // Handle file upload
        $dokumentasiPath = $request->file('dokumentasi_monitoring')->store('monitoring', 'public');

        $monitoring = Monitoring::create([
            'implementasi_id' => $data['implementasi_id'],
            'jumlah_bibit_ditanam' => $data['jumlah_bibit_ditanam'],
            'jumlah_bibit_mati' => $data['jumlah_bibit_mati'],
            'diameter_batang' => $data['diameter_batang'],
            'jumlah_daun' => $data['jumlah_daun'],
            'survival_rate' => json_encode($data['survival_rate']),
            'dokumentasi_monitoring_path' => $dokumentasiPath,
        ]);

        return response()->json([
            'message' => 'Monitoring created successfully',
            'data' => new FormResource($monitoring)
        ], 201);
    }

    public function getMonitoringData($implementasi_id)
    {
        $monitoring = Monitoring::where('implementasi_id', $implementasi_id)->firstOrFail();
        return response()->json(new FormResource($monitoring));
    }
}
