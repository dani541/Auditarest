<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    
    public function index()
{
    // Obtener datos para el gráfico de auditorías por mes
    $auditsByMonth = Audit::select(
            DB::raw('COUNT(*) as count'),
            DB::raw("DATE_FORMAT(date, '%b') as month"),
            DB::raw('MONTH(date) as month_num')
        )
        ->where('date', '>=', now()->subMonths(5))
        ->groupBy('month', 'month_num')
        ->orderBy('month_num')
        ->pluck('count', 'month');

    // Obtener el total de auditorías
    $totalAudits = Audit::count();

    // Obtener las últimas auditorías
    $recentAudits = Audit::with('restaurant')
        ->select('id', 'restaurant_id', 'total_score', 'created_at')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get()
        ->map(function($audit) {
            return [
                'name' => $audit->restaurant->name ?? 'Sin nombre',
                'date' => $audit->created_at->format('d/m/Y'),
                'score' => $audit->total_score
            ];
        });

    // Datos para el heatmap
    $heatmapData = Audit::select(
        DB::raw('DAYOFWEEK(created_at) as day_of_week'),
        DB::raw('HOUR(created_at) as hour'),
        DB::raw('COUNT(*) as count')
    )
    ->groupBy('day_of_week', 'hour')
    ->get()
    ->groupBy('day_of_week')
    ->map(function($dayGroup) {
        return $dayGroup->mapWithKeys(function($item) {
            return [$item->hour => ['count' => $item->count]];
        });
    });

    return view('admin.reports.index', [
        'auditsByMonth' => $auditsByMonth,
        'totalAudits' => $totalAudits,
        'recentAudits' => $recentAudits,
        'heatmapData' => $heatmapData
    ]);
}

        

    public function auditsByRestaurant(Request $request)
    {
        $restaurants = Restaurant::withCount('audits')
            ->with(['audits' => function($query) {
                $query->latest()->take(5);
            }])
            ->get();

        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = PDF::loadView('admin.reports.pdf.audits-by-restaurant', compact('restaurants'));
            return $pdf->download('informe-auditorias-por-restaurante.pdf');
        }

        return view('admin.reports.audits-by-restaurant', compact('restaurants'));
    }

    public function scoresByCategory()
    {
        $scores = Audit::select(
                DB::raw('AVG(infrastructure_score) as avg_infrastructure'),
                DB::raw('AVG(machinery_score) as avg_machinery'),
                DB::raw('AVG(hygiene_score) as avg_hygiene'),
                DB::raw('DATE(created_at) as date')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.reports.scores-by-category', compact('scores'));
    }
}