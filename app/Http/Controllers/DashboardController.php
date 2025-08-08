<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perencanaan;
use App\Models\Implementasi;
use App\Models\Monitoring;
use App\Http\Resources\FormResource;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $user = auth()->user();

        $perencanaanCount = Perencanaan::where('user_id', $user->id)->count();
        $implementasiCount = Implementasi::whereHas('perencanaan', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $monitoringCount = Monitoring::whereHas('implementasi.perencanaan', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $latestPerencanaan = Perencanaan::where('user_id', $user->id)
            ->with('implementasi.monitoring')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'stats' => [
                'perencanaan' => $perencanaanCount,
                'implementasi' => $implementasiCount,
                'monitoring' => $monitoringCount,
            ],
            'latest_activities' => FormResource::collection($latestPerencanaan)
        ]);
    }
}
