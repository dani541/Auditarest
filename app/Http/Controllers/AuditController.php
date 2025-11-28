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
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function indexAudi()
{
    if (!auth()->check() || !auth()->user()->hasRole('Auditor')) {
        abort(403, 'No tienes permiso para acceder a esta página');
    }

    $audits = Audit::where('auditor', auth()->user()->name)
        ->with('restaurant')
        ->latest()
        ->get();

    return view('auditor.index', compact('audits'));
}
 
public function index()
{
    $restaurants = Restaurant::with(['audits' => function($query) {
        $query->latest(); // Remove ->with('auditor')
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
/*
   public function store(Request $request)
{
    // Validar los datos del formulario
    $validated = $request->validate([
        'restaurant_id' => 'required|exists:restaurants,id',
        'auditor' => 'required|string|max:255',
        'date' => 'required|date',
        'supervisor' => 'required|string|max:255',
        'general_notes' => 'nullable|string',
        'infrastructure' => 'required|array',
        'machinery' => 'required|array',
        'hygiene' => 'required|array',
    ]);
    
    // Debug: Log the incoming request data
    \Log::info('Incoming request data:', [
        'infrastructure' => $validated['infrastructure'],
        'machinery' => $validated['machinery'],
        'hygiene' => $validated['hygiene']
    ]);

    // Iniciar una transacción de base de datos
    DB::beginTransaction();

    try {
        // Crear la auditoría principal
        $audit = Audit::create([
            'restaurant_id' => $validated['restaurant_id'],
            'auditor' => $validated['auditor'],
            'date' => $validated['date'],
            'supervisor' => $validated['supervisor'],
            'general_notes' => $validated['general_notes'] ?? null,
            'is_completed' => false,
            'total_score' => 0
        ]);

        // Transformar los datos de infraestructura
        $infrastructureData = $this->transformSectionData($validated['infrastructure']);
        \Log::info('Infrastructure data after transformation:', $infrastructureData);
        $infrastructure = $audit->infrastructure()->create($infrastructureData);
        \Log::info('Infrastructure created:', $infrastructure->toArray());

        // Transformar los datos de maquinaria
        $machineryData = $this->transformSectionData($validated['machinery']);
        \Log::info('Machinery data after transformation:', $machineryData);
        $machinery = $audit->machinery()->create($machineryData);
        \Log::info('Machinery created:', $machinery->toArray());

        // Transformar los datos de higiene
        $hygieneData = $this->transformSectionData($validated['hygiene']);
        \Log::info('Hygiene data after transformation:', $hygieneData);
        $hygiene = $audit->hygiene()->create($hygieneData);
        \Log::info('Hygiene created:', $hygiene->toArray());

        // Confirmar la transacción
        DB::commit();

        return redirect()->route('audits.show', $audit)
            ->with('success', 'Auditoría creada exitosamente.');

    } catch (\Exception $e) {
        // Revertir la transacción en caso de error
        DB::rollBack();
        \Log::error('Error al crear la auditoría: ' . $e->getMessage());
        
        return back()->withInput()
            ->with('error', 'Error al crear la auditoría: ' . $e->getMessage());
    }
}
*/

public function store(Request $request)
{
    \Log::info('Datos recibidos del formulario:', $request->all());

    try {
        // Validar los datos del formulario
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'auditor' => 'required|string|max:255',
            'date' => 'required|date',
            'supervisor' => 'required|string|max:255',
            'general_notes' => 'nullable|string',
            'infrastructure' => 'required|array',
            'machinery' => 'required|array',
            'hygiene' => 'required|array',
        ]);

        \Log::info('Datos validados:', $validated);

        // Iniciar una transacción de base de datos
        DB::beginTransaction();

        // Crear la auditoría principal
        $audit = Audit::create([
            'restaurant_id' => $validated['restaurant_id'],
            'auditor' => $validated['auditor'],
            'date' => $validated['date'],
            'supervisor' => $validated['supervisor'],
            'general_notes' => $validated['general_notes'] ?? null,
            'is_completed' => false,
            'total_score' => 0
        ]);

        \Log::info('Auditoría creada:', $audit->toArray());

        // Guardar las secciones de la auditoría
        $sections = ['infrastructure', 'machinery', 'hygiene'];
        foreach ($sections as $section) {
            if (isset($validated[$section])) {
                $sectionData = $this->transformSectionData($validated[$section]);
                $audit->$section()->create($sectionData);
                \Log::info("Sección {$section} creada:", $sectionData);
            }
        }

        // Confirmar la transacción
        DB::commit();

        return redirect()->route('auditor.dashboard', $audit)
            ->with('success', 'Auditoría creada exitosamente.');

    } catch (\Exception $e) {
        // Revertir la transacción en caso de error
        DB::rollBack();
        \Log::error('Error al crear la auditoría: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return back()->withInput()
            ->with('error', 'Error al crear la auditoría: ' . $e->getMessage());
    }
}




/**
 * Transforma los datos de una sección al formato esperado por el modelo.
 */
private function transformSectionData(array $sectionData): array
{
    $transformed = [];
    
    // If the data is already in the correct format (key-value pairs)
    if (isset($sectionData[0]) && is_array($sectionData[0])) {
        $sectionData = $sectionData[0];
    }
    
    foreach ($sectionData as $key => $value) {
        // Skip if the key is numeric (indicating it's part of an array we don't need)
        if (is_numeric($key)) {
            continue;
        }
        
        // Handle boolean values (checkboxes)
        if (is_bool($value) || $value === '1' || $value === '0' || $value === 1 || $value === 0) {
            $transformed[$key] = (bool)$value;
        } 
        // Handle string values
        else if (is_string($value)) {
            $transformed[$key] = $value ?: null;
        }
        // Handle arrays (for checkboxes that might be arrays)
        else if (is_array($value)) {
            // If it's an array with a single value, use that
            if (count($value) === 1 && isset($value[0])) {
                $transformed[$key] = $value[0] ?: null;
            } else {
                $transformed[$key] = json_encode($value);
            }
        }
        // Handle other types as is
        else {
            $transformed[$key] = $value;
        }
    }
    
    // Add audit_id if it's not already set
    if (!isset($transformed['audit_id']) && isset($this->audit)) {
        $transformed['audit_id'] = $this->audit->id;
    }
    
    // Ensure required fields for each section exist
    $sectionType = '';
    if (isset($sectionData['section_type'])) {
        $sectionType = $sectionData['section_type'];
    }
    
    // Define required fields for each section type
    $requiredFields = [];
    
    if (strpos(json_encode($sectionData), 'uniforms_condition') !== false || 
        strpos(json_encode($sectionData), 'hand_washing_condition') !== false) {
        // Hygiene section
        $requiredFields = [
            'uniforms_condition', 'hand_washing_condition', 'hygiene_kits_condition',
            'food_handling_condition', 'gloves_usage', 'hair_restraint_usage',
            'cleaning_supplies_condition', 'sanitization_procedures',
            'food_storage_condition', 'chemical_storage_condition'
        ];
    } else if (strpos(json_encode($sectionData), 'equipment_condition') !== false) {
        // Machinery section
        $requiredFields = [
            'equipment_condition', 'maintenance_status', 'safety_devices',
            'calibration_status', 'operational_status'
        ];
    } else {
        // Infrastructure section (default)
        $requiredFields = [
            'floor_condition', 'walls_condition', 'ceiling_condition',
            'lighting_condition', 'ventilation_condition', 'sanitary_condition',
            'equipment_condition', 'refrigeration_condition',
            'food_storage_condition', 'waste_management_condition'
        ];
    }
    
    // Ensure all required fields exist with default values
    foreach ($requiredFields as $field) {
        if (!array_key_exists($field, $transformed)) {
            $transformed[$field] = false;
        }
        
        // Add corresponding notes field if it doesn't exist
        $notesField = str_replace('_condition', '_notes', $field);
        if ($notesField !== $field && !array_key_exists($notesField, $transformed)) {
            $transformed[$notesField] = null;
        }
    }
    
    return $transformed;
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
    /*
    public function show(Audit $audit)
    {
        // Cargar las relaciones necesarias con eager loading
        $audit->load([
            'restaurant',  
            'infrastructure',
            'machinery',
            'hygiene'
        ]);

        // Debug: Log the loaded relationships
        \Log::info('Audit loaded with relationships:', [
            'audit_id' => $audit->id,
            'restaurant' => $audit->restaurant ? 'Loaded' : 'Not loaded',
            'infrastructure' => $audit->infrastructure ? 'Loaded' : 'Not loaded',
            'machinery' => $audit->machinery ? 'Loaded' : 'Not loaded',
            'hygiene' => $audit->hygiene ? 'Loaded' : 'Not loaded',
        ]);
        
        // Verificar si hay datos de cada sección
        $hasInfrastructure = $audit->infrastructure !== null;
        $hasMachinery = $audit->machinery !== null;
        $hasHygiene = $audit->hygiene !== null;
        
        // Obtener el restaurante de la auditoría
        $restaurant = $audit->restaurant;
        
        // Si el restaurante no está cargado, intenta cargarlo manualmente
        if (!$restaurant) {
            $audit->load('restaurant');
            $restaurant = $audit->restaurant;
        }

        // Obtener el nombre del auditor
        $auditorName = $audit->auditor;
        
        // Calcula el progreso basado en las secciones completadas
        $sections = array_filter([$hasInfrastructure, $hasMachinery, $hasHygiene]);
        $progress = count($sections) > 0 ? (int) ((count(array_filter($sections)) / count($sections)) * 100) : 0;

        return view('admin.audits.show', [
            'audit' => $audit,
            'restaurant' => $restaurant,
            'auditorName' => $auditorName,
            'progress' => $progress,
            'hasInfrastructure' => $hasInfrastructure,
            'hasMachinery' => $hasMachinery,
            'hasHygiene' => $hasHygiene
        ]);
}*/
/*
public function show($id)
{
    $audit = Audit::with([
        'restaurant',
        'infrastructure',
        'machinery',
        'hygiene',
        //'verificationItems' // Si tienes esta relación
    ])->findOrFail($id);

    // Ver datos en desarrollo
    if (config('app.debug')) {
        return response()->json([
            'audit' => $audit->toArray(),
            'relationships' => [
                'infrastructure' => $audit->infrastructure ? $audit->infrastructure->toArray() : null,
                'machinery' => $audit->machinery ? $audit->machinery->toArray() : null,
                'hygiene' => $audit->hygiene ? $audit->hygiene->toArray() : null
            ]
        ]);
    }

    return view('admin.audits.show', compact('audit'));
} */

    public function show($id)
{
    $audit = Audit::with([
        'restaurant',
        'infrastructure',
        'machinery',
        'hygiene'
    ])->findOrFail($id);

    // Si estamos en modo debug, mostramos los datos en formato JSON
  //  if (config('app.debug')) {
    //    return response()->json([
       //     'audit' => $audit,
       //     'relationships' => [
       //         'infrastructure' => $audit->infrastructure,
       //         'machinery' => $audit->machinery,
       //         'hygiene' => $audit->hygiene
       //     ]
     //   ]);
   // }

    return view('admin.audits.show', compact('audit'));
}
    /**
     * Exporta una auditoría a PDF
     */
    public function exportPdf(Audit $audit)
    {
        try {
            // Obtener el restaurante de la auditoría
            $restaurant = $audit->restaurant;
            
            if (!$restaurant) {
                abort(404, 'No se encontró el restaurante para esta auditoría');
            }
            
            // Cargar las relaciones necesarias
            $audit->load(['restaurant', 'infrastructure', 'machinery', 'hygiene']);
            
            // Generar la vista del PDF
            $pdf = \PDF::loadView('pdf.audit', [
                'audit' => $audit,
                'restaurant' => $restaurant
            ]);
            
            // Descargar el PDF
            return $pdf->download("auditoria-{$audit->id}.pdf");
            
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
