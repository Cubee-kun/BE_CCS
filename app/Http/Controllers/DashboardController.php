<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perencanaan;
use App\Models\Implementasi;
use App\Models\Monitoring;
use App\Models\Evaluasi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
     *     summary="Get real-time dashboard statistics, charts, and recent activities",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard data (realtime)",
     *         @OA\JsonContent(
     *             @OA\Property(property="stats", type="object"),
     *             @OA\Property(property="charts", type="object"),
     *             @OA\Property(property="breakdowns", type="object"),
     *             @OA\Property(property="recent_activities", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function stats()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Totals (real-time per user)
            $totalPerencanaan = Perencanaan::where('user_id', $user->id)->count();

            $totalImplementasi = Implementasi::whereHas('perencanaan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

            $totalMonitoring = Monitoring::whereHas('implementasi.perencanaan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

            $totalEvaluasi = Evaluasi::whereHas('implementasi.perencanaan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

            // Survival rate averages (real-time)
            $avgSurvivalMonitoring = round(
                (float) Monitoring::whereHas('implementasi.perencanaan', fn($q) => $q->where('user_id', $user->id))
                    ->avg('survival_rate'),
                2
            );

            $avgSurvivalEvaluasi = round(
                (float) Evaluasi::whereHas('implementasi.perencanaan', fn($q) => $q->where('user_id', $user->id))
                    ->avg('survival_rate'),
                2
            );

            // Time-series last 30 days for charts
            $perTanggal = $this->timeSeries(
                Perencanaan::query()->where('user_id', $user->id)
            );

            $impTanggal = $this->timeSeries(
                Implementasi::query()->whereHas('perencanaan', fn($q) => $q->where('user_id', $user->id))
            );

            $monTanggal = $this->timeSeries(
                Monitoring::query()->whereHas('implementasi.perencanaan', fn($q) => $q->where('user_id', $user->id))
            );

            $evaTanggal = $this->timeSeries(
                Evaluasi::query()->whereHas('implementasi.perencanaan', fn($q) => $q->where('user_id', $user->id))
            );

            // Breakdown jenis_kegiatan (untuk pie/donut)
            $jenisKegiatan = Perencanaan::where('user_id', $user->id)
                ->select('jenis_kegiatan', DB::raw('COUNT(*) as total'))
                ->groupBy('jenis_kegiatan')
                ->orderByDesc('total')
                ->get();

            // Recent activities (gabungan 10 terbaru)
            $recent = collect()
                ->merge(
                    Perencanaan::where('user_id', $user->id)
                        ->select(['id', 'nama_perusahaan', 'jenis_kegiatan', 'tanggal_pelaksanaan', 'created_at'])
                        ->latest()->take(10)->get()
                        ->map(fn($x) => [
                            'type' => 'perencanaan',
                            'id' => $x->id,
                            'title' => $x->nama_perusahaan,
                            'subtitle' => $x->jenis_kegiatan,
                            'date' => $x->tanggal_pelaksanaan,
                            'created_at' => $x->created_at,
                        ])
                )
                ->merge(
                    Implementasi::whereHas('perencanaan', fn($q) => $q->where('user_id', $user->id))
                        ->select(['id', 'pic_koorlap', 'created_at'])
                        ->latest()->take(10)->get()
                        ->map(fn($x) => [
                            'type' => 'implementasi',
                            'id' => $x->id,
                            'title' => $x->pic_koorlap,
                            'subtitle' => 'Implementasi',
                            'date' => optional($x->created_at)?->toDateString(),
                            'created_at' => $x->created_at,
                        ])
                )
                ->merge(
                    Monitoring::whereHas('implementasi.perencanaan', fn($q) => $q->where('user_id', $user->id))
                        ->select(['id', 'survival_rate', 'created_at'])
                        ->latest()->take(10)->get()
                        ->map(fn($x) => [
                            'type' => 'monitoring',
                            'id' => $x->id,
                            'title' => 'Monitoring',
                            'subtitle' => 'Survival '.$x->survival_rate.'%',
                            'date' => optional($x->created_at)?->toDateString(),
                            'created_at' => $x->created_at,
                        ])
                )
                ->merge(
                    Evaluasi::whereHas('implementasi.perencanaan', fn($q) => $q->where('user_id', $user->id))
                        ->select(['id', 'kondisi_kesehatan', 'survival_rate', 'created_at'])
                        ->latest()->take(10)->get()
                        ->map(fn($x) => [
                            'type' => 'evaluasi',
                            'id' => $x->id,
                            'title' => 'Evaluasi',
                            'subtitle' => $x->kondisi_kesehatan.' ('.$x->survival_rate.'%)',
                            'date' => optional($x->created_at)?->toDateString(),
                            'created_at' => $x->created_at,
                        ])
                )
                ->sortByDesc('created_at')
                ->values()
                ->take(10);

            return response()->json([
                'stats' => [
                    'total_perencanaan' => $totalPerencanaan,
                    'total_implementasi' => $totalImplementasi,
                    'total_monitoring' => $totalMonitoring,
                    'total_evaluasi' => $totalEvaluasi,
                    'avg_survival_monitoring' => $avgSurvivalMonitoring,
                    'avg_survival_evaluasi' => $avgSurvivalEvaluasi,
                ],
                'charts' => [
                    'perencanaan_per_hari' => $perTanggal,
                    'implementasi_per_hari' => $impTanggal,
                    'monitoring_per_hari' => $monTanggal,
                    'evaluasi_per_hari' => $evaTanggal,
                ],
                'breakdowns' => [
                    'jenis_kegiatan' => $jenisKegiatan,
                ],
                'recent_activities' => $recent,
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard stats error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'exception' => $e,
            ]);

            return response()->json([
                'error' => 'Failed to fetch dashboard statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function timeSeries($baseQuery, string $dateColumn = 'created_at', int $days = 30): array
    {
        $end = Carbon::today();
        $start = (clone $end)->subDays($days - 1);
        $range = collect();
        for ($d = (clone $start); $d->lte($end); $d->addDay()) {
            $range->push($d->format('Y-m-d'));
        }

        $rows = (clone $baseQuery)
            ->whereBetween($dateColumn, [$start->startOfDay(), $end->endOfDay()])
            ->select(DB::raw("DATE($dateColumn) as d"), DB::raw('COUNT(*) as c'))
            ->groupBy('d')
            ->pluck('c', 'd');

        return $range->map(fn($date) => [
            'date' => $date,
            'count' => (int) ($rows[$date] ?? 0),
        ])->all();
    }
}
