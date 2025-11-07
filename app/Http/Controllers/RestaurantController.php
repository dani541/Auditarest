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
    public function index()
    {
        try {
            $restaurants = Restaurant::orderBy('name')->paginate(10);
            return view('admin.restaurants.index', compact('restaurants'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar la lista de restaurantes: ' . $e->getMessage());
        }
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
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $restaurant = Restaurant::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $restaurant,
                'message' => 'Restaurante creado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el restaurante',
                'error' => $e->getMessage()
            ], 500);
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
            
            return response()->json([
                'success' => true,
                'data' => $restaurant
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurante no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualizar un restaurante existente.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'contact_name' => 'sometimes|required|string|max:100',
            'contact_phone' => 'sometimes|required|string|max:20',
            'contact_email' => 'sometimes|required|email|unique:restaurants,contact_email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $restaurant = Restaurant::findOrFail($id);
            $restaurant->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $restaurant,
                'message' => 'Restaurante actualizado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el restaurante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un restaurante.
     */
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

}
