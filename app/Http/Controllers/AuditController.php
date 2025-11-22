<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\Restaurant;
use App\Models\VerificationItem;
use App\Models\VerificationResponse;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuditController extends Controller
{

 

public function index()
{
    $restaurants = Restaurant::with(['audits' => function($query) {
        $query->with('auditor')->latest();
    }])->get();

    return view('admin.audits.index', compact('restaurants'));
}

public function selectRestaurant()
{
    $restaurants = Restaurant::all();
    return view('admin.audits.select-restaurant', compact('restaurants'));
}

public function create(Restaurant $restaurant)
{
    $verificationItems = VerificationItem::orderBy('category')
        ->orderBy('order')
        ->get()
        ->groupBy('category');

    return view('admin.audits.create', [
        'restaurant' => $restaurant,
        'verificationItems' => $verificationItems
    ]);
}







    /**
     * Almacena una nueva auditoría
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'audit_date' => 'required|date',
            'evidence.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:5120',
        ]);

        try {
            DB::beginTransaction();

            // Crear la auditoría
            $audit = Audit::create([
                'scheduled_date' => $request->audit_date,
                'status' => 'completada',
                'user_id' => auth()->id(),
                'restaurant_id' => $restaurant->id,
                'observations' => $request->general_observations,
            ]);

            // Procesar las respuestas de verificación
            foreach ($request->all() as $key => $value) {
                if (str_starts_with($key, 'status_')) {
                    $itemId = str_replace('status_', '', $key);
                    
                    VerificationResponse::create([
                        'audit_id' => $audit->id,
                        'verification_item_id' => $itemId,
                        'status' => $value,
                        'corrective_measure' => $request->input("corrective_measure_{$itemId}"),
                        'temperature' => $request->input("temperature_{$itemId}"),
                    ]);
                }
            }

            // Procesar evidencias
            if ($request->has('evidence')) {
                foreach ($request->evidence as $evidence) {
                    $path = $evidence->store("audits/{$audit->id}/evidences", 'public');
                    
                    $audit->evidences()->create([
                        'path' => $path,
                        'original_name' => $evidence->getClientOriginalName(),
                        'mime_type' => $evidence->getClientMimeType(),
                        'size' => $evidence->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.restaurants.audits.show', [$restaurant->id, $audit->id])
                ->with('success', 'Auditoría registrada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al guardar la auditoría: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar la auditoría. Por favor, inténtalo de nuevo.');
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
