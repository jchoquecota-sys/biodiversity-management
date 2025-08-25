<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use App\Models\Clase;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrdenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Orden::with('clase')->withCount('familias');

            return DataTables::of($query)
                ->addColumn('clase_nombre', function ($row) {
                    return $row->clase ? $row->clase->nombre : 'Sin clase';
                })
                ->addColumn('familias_count', function ($row) {
                    return '<span class="badge badge-info">' . $row->familias_count . ' familias</span>';
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="btn-group">';
                    $actions .= '<a href="' . route('admin.ordens.show', $row->idorden) . '" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('admin.ordens.edit', $row->idorden) . '" class="btn btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                    
                    if ($row->familias_count == 0) {
                        $actions .= '<form action="' . route('admin.ordens.destroy', $row->idorden) . '" method="POST" onsubmit="return confirm(\'¿Está seguro de eliminar este orden?\');" style="display: inline-block;">';
                        $actions .= csrf_field() . method_field('DELETE');
                        $actions .= '<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                        $actions .= '</form>';
                    } else {
                        $actions .= '<button type="button" class="btn btn-sm btn-danger" disabled title="No se puede eliminar: tiene familias asociadas"><i class="fas fa-trash"></i></button>';
                    }
                    
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['familias_count', 'action'])
                ->make(true);
        }

        return view('admin.ordens.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clases = Clase::with('reino')->orderBy('nombre')->get();
        $reinos = \App\Models\Reino::orderBy('nombre')->get();
        return view('admin.ordens.create', compact('clases', 'reinos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:ordens,nombre',
            'definicion' => 'required|string|max:255',
            'idclase' => 'required|exists:clases,idclase'
        ], [
            'nombre.required' => 'El nombre del orden es obligatorio.',
            'nombre.unique' => 'Ya existe un orden con este nombre.',
            'definicion.required' => 'La definición es obligatoria.',
            'idclase.required' => 'Debe seleccionar una clase.',
            'idclase.exists' => 'La clase seleccionada no es válida.'
        ]);

        Orden::create([
            'nombre' => $request->nombre,
            'definicion' => $request->definicion,
            'idclase' => $request->idclase
        ]);

        return redirect()->route('admin.ordens.index')
            ->with('success', 'Orden creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Orden $orden)
    {
        $orden->load(['clase', 'familias']);
        
        return view('admin.ordens.show', compact('orden'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orden $orden)
    {
        $clases = Clase::with('reino')->orderBy('nombre')->get();
        $reinos = \App\Models\Reino::orderBy('nombre')->get();
        return view('admin.ordens.edit', compact('orden', 'clases', 'reinos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orden $orden)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:ordens,nombre,' . $orden->idorden . ',idorden',
            'definicion' => 'required|string|max:255',
            'idclase' => 'required|exists:clases,idclase'
        ], [
            'nombre.required' => 'El nombre del orden es obligatorio.',
            'nombre.unique' => 'Ya existe un orden con este nombre.',
            'definicion.required' => 'La definición es obligatoria.',
            'idclase.required' => 'Debe seleccionar una clase.',
            'idclase.exists' => 'La clase seleccionada no es válida.'
        ]);

        $orden->update([
            'nombre' => $request->nombre,
            'definicion' => $request->definicion,
            'idclase' => $request->idclase
        ]);

        return redirect()->route('admin.ordens.index')
            ->with('success', 'Orden actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orden $orden)
    {
        // Verificar que no tenga familias asociadas
        if ($orden->familias()->count() > 0) {
            return redirect()->route('admin.ordens.index')
                ->with('error', 'No se puede eliminar el orden porque tiene familias asociadas.');
        }

        $orden->delete();

        return redirect()->route('admin.ordens.index')
            ->with('success', 'Orden eliminado exitosamente.');
    }

    /**
     * Export ordens to CSV
     */
    public function export()
    {
        $ordens = Orden::with('clase')->withCount('familias')->orderBy('nombre')->get();
        
        $filename = 'ordens_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($ordens) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'ID',
                'Nombre',
                'Definición',
                'Clase',
                'Número de Familias',
                'Fecha de Creación'
            ]);
            
            // Data
            foreach ($ordens as $orden) {
                fputcsv($file, [
                    $orden->idorden,
                    $orden->nombre,
                    $orden->definicion,
                    $orden->clase ? $orden->clase->nombre : 'N/A',
                    $orden->familias_count,
                    $orden->created_at ? $orden->created_at->format('d/m/Y H:i:s') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
