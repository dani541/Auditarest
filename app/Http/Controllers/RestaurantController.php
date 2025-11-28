<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RestaurantController extends Controller
{
    /**
     * Mostrar una lista de restaurantes.
     */
    /*
    public function index()
    {
        try {
            $restaurants = Restaurant::orderBy('name')->paginate(10);
            return view('admin.restaurants.index', compact('restaurants'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar la lista de restaurantes: ' . $e->getMessage());
        }
    }
    */
    public function index()
{
    $restaurants = Restaurant::orderBy('name')->paginate(10);
    $cities = Restaurant::select('city')->distinct()->orderBy('city')->pluck('city');
    return view('admin.restaurants.index', compact('restaurants', 'cities'));
}
    /**
     * Mostrar el formulario para crear un nuevo restaurante.
     */
    public function create()
    {
        return view('admin.restaurants.create');
    }

    /**
     * Almacenar un nuevo restaurante en la base de datos.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'contact_name' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|unique:restaurants,contact_email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $restaurant = Restaurant::create($request->all());
            
            return redirect()->route('admin.restaurants.index')
                ->with('success', 'Restaurante creado exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el restaurante: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar un restaurante específico.
     */
    public function show($id)
    {
        try {
            $restaurant = Restaurant::find($id);
            
            if (!$restaurant) {
                return redirect()->route('admin.restaurants.index')
                    ->with('error', 'Restaurante no encontrado');
            }
            
            return view('admin.restaurants.show', compact('restaurant'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.restaurants.index')
                ->with('error', 'Error al cargar el restaurante: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar el formulario para editar un restaurante.
     */
    public function edit($id)
    {
        try {
            $restaurant = Restaurant::findOrFail($id);
            
            return view('admin.restaurants.edit', compact('restaurant'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.restaurants.index')
                ->with('error', 'Restaurante no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar un restaurante existente.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'contact_name' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|unique:restaurants,contact_email,' . $id,
            'opening_hours' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $restaurant = Restaurant::findOrFail($id);
            $restaurant->update($request->all());

            return redirect()->route('admin.restaurants.edit', $restaurant->id)
                ->with('success', '¡Los datos del restaurante se han actualizado correctamente!');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el restaurante: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar un restaurante.
     */
    /*
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $restaurant = Restaurant::findOrFail($id);
            
            // Verificar si el restaurante tiene auditorías asociadas
            if ($restaurant->audits()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el restaurante porque tiene auditorías asociadas'
                ], 400);
            }
            
            $restaurant->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Restaurante eliminado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el restaurante',
                'error' => $e->getMessage()
            ], 500);
        }
    } */
public function destroy($id)
{
    DB::beginTransaction();
    try {
        $restaurant = Restaurant::findOrFail($id);
        
        // Eliminar auditorías asociadas
        $restaurant->audits()->delete();
        
        // Luego eliminar el restaurante
        $restaurant->delete();
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Restaurante y sus auditorías eliminados exitosamente'
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el restaurante',
            'error' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Generar PDF para un restaurante específico.
     */
    public function generatePdf($id)
    {
        try {
            $restaurant = Restaurant::findOrFail($id);
            
            $pdf = PDF::loadView('admin.restaurants.pdf', compact('restaurant'));
            
            return $pdf->download('restaurante-' . $restaurant->id . '.pdf');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.restaurants.show', $id)
                ->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Obtener las auditorías de un restaurante específico.
     */
    public function getAudits($id)
    {
        try {
            $restaurant = Restaurant::with('audits')->find($id);
            
            if (!$restaurant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Restaurante no encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $restaurant->audits
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las auditorías del restaurante',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    /**
     * 
     */
}
