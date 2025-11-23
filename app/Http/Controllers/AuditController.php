<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Audit;
use App\Models\AuditCategory;
use App\Models\Restaurant;
use App\Models\VerificationItem;
use App\Models\VerificationResponse;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuditController extends Controller
{



public function indexAudi()
{
    if (!auth()->check() || !auth()->user()->hasRole('Auditor')) {
        abort(403, 'No tienes permiso para acceder a esta página');
    }

    $audits = Audit::where('user_id', auth()->id())
        ->with('restaurant')
        ->latest()
        ->get();

    return view('auditor.index', compact('audits'));
}
 

public function index()
{
    $restaurants = Restaurant::with(['audits' => function($query) {
        $query->with('auditor')->latest();
    }])->get();

    return view('admin.audits.index', compact('restaurants'));
}
/*
public function selectRestaurant()
{
    $restaurants = Restaurant::all();
    $categories = AuditCategory::with('questions')->orderBy('order')->get();
    
    return view('admin.audits.create', compact('restaurants', 'categories'));
}
*/
public function selectRestaurant()
{
    $restaurants = Restaurant::all();
    
    if ($restaurants->isEmpty()) {
        return redirect()->route('audits.index')
            ->with('error', 'No hay restaurantes disponibles para auditar.');
    }
    
    // If there's only one restaurant, redirect directly to create
    if ($restaurants->count() === 1) {
        return redirect()->route('audits.create', $restaurants->first());
    }
    
    return view('admin.audits.select-restaurant', compact('restaurants'));
}


public function create()
{
    $restaurants = Restaurant::all();
    $categories = AuditCategory::with(['questions' => function($query) {
        $query->orderBy('order');
    }])->orderBy('order')->get();
    
    if ($restaurants->isEmpty()) {
        return redirect()->route('audits.index')
            ->with('error', 'No hay restaurantes disponibles para auditar.');
    }
    
    return view('admin.audits.create', compact('restaurants', 'categories'));
}

    






   public function store(Request $request)
{

    $validated = $request->validate([
        'restaurant_id' => 'required|exists:restaurants,id',
        'auditor' => 'required|string|max:255',
        'date' => 'required|date',
        'supervisor' => 'required|string|max:255',
        'responsable' => 'required|string|max:255',
        'incidencias_comentarios' => 'nullable|string',
        'verification' => 'required|array',
    ]);

    DB::beginTransaction();
    try {
        // Create the audit
        $audit = Audit::create([
            'restaurant_id' => $validated['restaurant_id'],
            'auditor' => $validated['auditor'],
            'date' => $validated['date'],
            'supervisor' => $validated['supervisor'],
            'responsable' => $validated['responsable'],
            'incidencias_comentarios' => $validated['incidencias_comentarios'] ?? null,
            'status' => 'completada',
            'auditor_id' => auth()->id(),
        ]);

        // Save verification responses
        foreach ($validated['verification'] as $section => $items) {
            foreach ($items as $index => $item) {
                // Find the verification item
                $verificationItem = VerificationItem::where('category', strtoupper($section))
                    ->where('order', $index + 1)
                    ->first();

                if ($verificationItem) {
                    VerificationResponse::create([
                        'audit_id' => $audit->id,
                        'verification_item_id' => $verificationItem->id,
                        'complies' => (bool)($item['complies'] ?? false),
                        'notes' => $item['notes'] ?? null,
                        'value' => $item['value'] ?? null,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('admin.audits.show', $audit)
            ->with('success', 'Auditoría creada correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al crear la auditoría: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Error al crear la auditoría. Por favor, inténtelo de nuevo.')
            ->withInput();
    }
}

/**
 * Save verification items for an audit
 */
private function saveVerificationItems($audit, $section, $items)
{
    foreach ($items as $index => $item) {
        $verificationItem = new \App\Models\VerificationItem([
            'section' => $section,
            'item_index' => $index,
            'complies' => (bool)$item['complies'],
            'notes' => $item['notes'] ?? null,
        ]);
        
        $audit->verificationItems()->save($verificationItem);
    }
}


    

    /**
     * Muestra el detalle de una auditoría
     */
    public function show(Restaurant $restaurant, Audit $audit)
    {
        $audit->load([
            'verificationItems',
            'evidences',
            'auditor',
            'restaurant'
        ]);

        $groupedItems = $audit->verificationItems->groupBy('category');

        return view('admin.audits.show', [
            'audit' => $audit,
            'restaurant' => $restaurant,
            'groupedItems' => $groupedItems
        ]);
    }

    /**
     * Exporta una auditoría a PDF
     */
    public function exportPdf(Restaurant $restaurant, Audit $audit)
    {
        try {
            return $this->pdfService->generateAuditPdf($audit);
        } catch (\Exception $e) {
            \Log::error('Error al generar PDF: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Sube una evidencia mediante AJAX
     */
    public function uploadEvidence(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->store('temp/evidences', 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    /**
     * Elimina una evidencia
     */
    public function deleteEvidence($evidenceId)
    {
        $evidence = \App\Models\Evidence::findOrFail($evidenceId);
        
        // Eliminar el archivo físico
        Storage::disk('public')->delete($evidence->path);
        
        // Eliminar el registro de la base de datos
        $evidence->delete();
        
        return response()->json(['success' => true]);
    }
}
