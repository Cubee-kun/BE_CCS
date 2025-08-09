<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perencanaan;
use App\Models\Implementasi;
use App\Models\Monitoring;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/stats",
     *     tags={"Dashboard"},
     *     summary="Get dashboard statistics and recent activities",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Dashboard data")
     * )
     */
    public function stats()
    {
        $user = auth()->user();

        $totalPerencanaan = Perencanaan::where('user_id', $user->id)->count();
        $totalImplementasi = Implementasi::whereHas('perencanaan', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $totalMonitoring = Monitoring::whereHas('implementasi.perencanaan', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $recentActivities = Perencanaan::where('user_id', $user->id)
            ->with(['implementasi', 'implementasi.monitoring'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'stats' => [
                'total_perencanaan' => $totalPerencanaan,
                'total_implementasi' => $totalImplementasi,
                'total_monitoring' => $totalMonitoring,
            ],
            'recent_activities' => $recentActivities
        ]);
    }
}
