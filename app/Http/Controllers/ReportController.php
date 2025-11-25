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
        return view('admin.reports.index');
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