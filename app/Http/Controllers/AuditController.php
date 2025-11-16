<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    /**
     * Mostrar el historial de auditorías de un restaurante
     */
    public function index($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $audits = $restaurant->audits()->with('user')->latest()->get();
        
        return view('admin.restaurants.audits.index', compact('restaurant', 'audits'));
    }
    
    /**
     * Mostrar los detalles de una auditoría específica
     */
    public function show($restaurantId, $auditId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $audit = $restaurant->audits()->with('user')->findOrFail($auditId);
        
        return view('admin.restaurants.audits.show', compact('restaurant', 'audit'));
    }
}
