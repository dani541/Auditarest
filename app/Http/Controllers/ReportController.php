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
        // Get total number of audits
        $totalAudits = \App\Models\Audit::count();
        
        // Get audits by month for the last 6 months
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        
        $auditsByMonth = \App\Models\Audit::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
            
        // Format the data for the chart
        $monthlyData = [];
        $monthNames = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('n');
            $year = $date->format('Y');
            $monthNames[] = $date->format('M');
            
            $auditCount = $auditsByMonth->first(function($item) use ($month, $year) {
                return $item->month == $month && $item->year == $year;
            });
            
            $monthlyData[] = $auditCount ? $auditCount->count : 0;
        }
        
        // Get recent audits
        $recentAudits = \App\Models\Audit::with('restaurant')
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
        
        return view('admin.reports.index', [
            'totalAudits' => $totalAudits,
            'monthlyData' => $monthlyData,
            'monthNames' => $monthNames,
            'recentAudits' => $recentAudits
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