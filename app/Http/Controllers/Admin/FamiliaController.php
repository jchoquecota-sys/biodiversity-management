<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Familia;
use App\Models\Orden;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FamiliaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $familias = Familia::with(['orden.clase'])
                ->withCount('biodiversityCategories')
                ->select('familias.*');

            return DataTables::of($familias)
                ->addColumn('orden_nombre', function ($familia) {
                    return $familia->orden ? $familia->orden->nombre : 'N/A';
                })
                ->addColumn('clase_nombre', function ($familia) {
                    return $familia->orden && $familia->orden->clase ? $familia->orden->clase->nombre : 'N/A';
                })
                ->addColumn('categorias_count', function ($familia) {
                    return $familia->biodiversity_categories_count;
                })
                ->addColumn('action', function ($familia) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="' . route('admin.familias.show', $familia->idfamilia) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('admin.familias.edit', $familia->idfamilia) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>';
                    
                    if ($familia->biodiversity_categories_count == 0) {
                        $actions .= '<form method="POST" action="' . route('admin.familias.destroy', $familia->idfamilia) . '" style="display:inline;" onsubmit="return confirm(\'¿Está seguro de eliminar esta familia?\')">';
                        $actions .= csrf_field() . method_field('DELETE');
                        $actions .= '<button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                        $actions .= '</form>';
                    } else {
                        $actions .= '<button class="btn btn-danger btn-sm" disabled title="No se puede eliminar porque tiene categorías asociadas"><i class="fas fa-trash"></i></button>';
                    }
                    
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.familias.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ordens = Orden::with('clase.reino')->orderBy('nombre')->get();
        $reinos = \App\Models\Reino::orderBy('nombre')->get();
        $clases = \App\Models\Clase::with('reino')->orderBy('nombre')->get();
        return view('admin.familias.create', compact('ordens', 'reinos', 'clases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:familias,nombre',
            'definicion' => 'required|string|max:255',
            'idorden' => 'required|exists:ordens,idorden'
        ], [
            'nombre.required' => 'El nombre de la familia es obligatorio.',
            'nombre.unique' => 'Ya existe una familia con este nombre.',
            'definicion.required' => 'La definición es obligatoria.',
            'idorden.required' => 'Debe seleccionar un orden.',
            'idorden.exists' => 'El orden seleccionado no es válido.'
        ]);

        Familia::create([
            'nombre' => $request->nombre,
            'definicion' => $request->definicion,
            'idorden' => $request->idorden
        ]);

        return redirect()->route('admin.familias.index')
            ->with('success', 'Familia creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Familia $familia)
    {
        $familia->load(['orden.clase', 'biodiversityCategories']);
        
        return view('admin.familias.show', compact('familia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Familia $familia)
    {
        $ordens = Orden::with('clase.reino')->orderBy('nombre')->get();
        $reinos = \App\Models\Reino::orderBy('nombre')->get();
        $clases = \App\Models\Clase::with('reino')->orderBy('nombre')->get();
        return view('admin.familias.edit', compact('familia', 'ordens', 'reinos', 'clases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Familia $familia)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:familias,nombre,' . $familia->idfamilia . ',idfamilia',
            'definicion' => 'required|string|max:255',
            'idorden' => 'required|exists:ordens,idorden'
        ], [
            'nombre.required' => 'El nombre de la familia es obligatorio.',
            'nombre.unique' => 'Ya existe una familia con este nombre.',
            'definicion.required' => 'La definición es obligatoria.',
            'idorden.required' => 'Debe seleccionar un orden.',
            'idorden.exists' => 'El orden seleccionado no es válido.'
        ]);

        $familia->update([
            'nombre' => $request->nombre,
            'definicion' => $request->definicion,
            'idorden' => $request->idorden
        ]);

        return redirect()->route('admin.familias.index')
            ->with('success', 'Familia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Familia $familia)
    {
        // Verificar que no tenga categorías de biodiversidad asociadas
        if ($familia->biodiversityCategories()->count() > 0) {
            return redirect()->route('admin.familias.index')
                ->with('error', 'No se puede eliminar la familia porque tiene categorías de biodiversidad asociadas.');
        }

        $familia->delete();

        return redirect()->route('admin.familias.index')
            ->with('success', 'Familia eliminada exitosamente.');
    }

    /**
     * Export familias to CSV
     */
    public function export()
    {
        $familias = Familia::with(['orden.clase'])->orderBy('nombre')->get();
        
        $filename = 'familias_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($familias) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'ID',
                'Nombre',
                'Definición',
                'Orden',
                'Clase',
                'Fecha de Creación'
            ]);
            
            // Data
            foreach ($familias as $familia) {
                fputcsv($file, [
                    $familia->idfamilia,
                    $familia->nombre,
                    $familia->definicion,
                    $familia->orden ? $familia->orden->nombre : 'N/A',
                    $familia->orden && $familia->orden->clase ? $familia->orden->clase->nombre : 'N/A',
                    $familia->created_at ? $familia->created_at->format('d/m/Y H:i:s') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
