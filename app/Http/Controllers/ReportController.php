<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditInfrastructure;
use App\Models\AuditMachinery;
use App\Models\AuditHygiene;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        // Total de auditorías
        $totalAudits = Audit::count();

        // Últimos 6 meses
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();

        // Auditorías por mes (PostgreSQL)
        $auditsByMonth = Audit::select(
                DB::raw('EXTRACT(MONTH FROM created_at) AS month'),
                DB::raw('EXTRACT(YEAR FROM created_at) AS year'),
                DB::raw('COUNT(*) AS count')
            )
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Formatear datos para el gráfico
        $monthlyData = [];
        $monthNames = [];
        $startDate = now()->subMonths(5)->startOfMonth();
        $endDate = now()->endOfMonth();

        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1M'),
            $endDate->modify('+1 day') // para incluir el último mes
        );

        foreach ($period as $date) {
            $month = (int)$date->format('n');
            $year = (int)$date->format('Y');
            $monthNames[] = $date->format('M');

            $auditCount = $auditsByMonth->first(function($item) use ($month, $year) {
                return (int)$item->month === $month && (int)$item->year === $year;
            });

            $monthlyData[] = $auditCount ? $auditCount->count : 0;
        }

        // Últimas 5 auditorías
        $recentAudits = Audit::with('restaurant')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($audit) {
                return [
                    'name' => $audit->restaurant->name ?? 'Sin restaurante',
                    'date' => $audit->created_at->format('d/m/Y'),
                    'score' => $audit->total_score ?? 0
                ];
            });

        // Promedio por categoría
        $avgScores = [];

        $hasInfrastructure = AuditInfrastructure::exists();
        $hasMachinery = AuditMachinery::exists();
        $hasHygiene = AuditHygiene::exists();

        $useDemoData = !($hasInfrastructure || $hasMachinery || $hasHygiene);

        if ($useDemoData) {
            $avgScores = [
                'infraestructura' => ['score' => rand(70, 95), 'color' => 'rgba(54, 162, 235, 0.6)', 'borderColor' => 'rgba(54, 162, 235, 1)'],
                'maquinaria' => ['score' => rand(60, 90), 'color' => 'rgba(255, 99, 132, 0.6)', 'borderColor' => 'rgba(255, 99, 132, 1)'],
                'higiene' => ['score' => rand(80, 98), 'color' => 'rgba(75, 192, 192, 0.6)', 'borderColor' => 'rgba(75, 192, 192, 1)']
            ];
        } else {
            $avgScores = [
                'infraestructura' => ['score' => (float)number_format(AuditInfrastructure::avg('percentage'), 2), 'color' => 'rgba(54, 162, 235, 0.6)', 'borderColor' => 'rgba(54, 162, 235, 1)'],
                'maquinaria' => ['score' => (float)number_format(AuditMachinery::avg('percentage'), 2), 'color' => 'rgba(255, 99, 132, 0.6)', 'borderColor' => 'rgba(255, 99, 132, 1)'],
                'higiene' => ['score' => (float)number_format(AuditHygiene::avg('percentage'), 2), 'color' => 'rgba(75, 192, 192, 0.6)', 'borderColor' => 'rgba(75, 192, 192, 1)']
            ];
        }

        $radarLabels = array_map('ucfirst', array_keys($avgScores));
        $radarData = array_map(fn($item) => (float)$item['score'], $avgScores);
        $radarBackgroundColors = array_column($avgScores, 'color');
        $radarBorderColors = array_column($avgScores, 'borderColor');

        return view('admin.reports.index', compact(
            'totalAudits', 'monthlyData', 'monthNames',
            'recentAudits', 'radarLabels', 'radarData',
            'radarBackgroundColors', 'radarBorderColors',
            'avgScores', 'useDemoData'
        ));
    }

    public function auditsByRestaurant(Request $request)
    {
        $restaurants = Restaurant::withCount('audits')
            ->with(['audits' => fn($q) => $q->latest()->take(5)])
            ->get();

        if ($request->export === 'pdf') {
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
