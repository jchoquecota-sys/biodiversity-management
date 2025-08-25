<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Clase::withCount('ordens');

            return DataTables::of($query)
                ->addColumn('ordens_count', function ($row) {
                    return '<span class="badge badge-info">' . $row->ordens_count . ' órdenes</span>';
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="btn-group">';
                    $actions .= '<a href="' . route('admin.clases.show', $row->idclase) . '" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('admin.clases.edit', $row->idclase) . '" class="btn btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                    
                    if ($row->ordens_count == 0) {
                        $actions .= '<form action="' . route('admin.clases.destroy', $row->idclase) . '" method="POST" onsubmit="return confirm(\'¿Está seguro de eliminar esta clase?\');" style="display: inline-block;">';
                        $actions .= csrf_field() . method_field('DELETE');
                        $actions .= '<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                        $actions .= '</form>';
                    } else {
                        $actions .= '<button type="button" class="btn btn-sm btn-danger" disabled title="No se puede eliminar: tiene órdenes asociados"><i class="fas fa-trash"></i></button>';
                    }
                    
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['ordens_count', 'action'])
                ->make(true);
        }

        return view('admin.clases.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reinos = \App\Models\Reino::orderBy('nombre')->get();
        return view('admin.clases.create', compact('reinos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:clases,nombre',
            'definicion' => 'required|string|max:255',
            'idreino' => 'required|exists:reinos,id'
        ], [
            'nombre.required' => 'El nombre de la clase es obligatorio.',
            'nombre.unique' => 'Ya existe una clase con este nombre.',
            'definicion.required' => 'La definición es obligatoria.',
            'idreino.required' => 'Debe seleccionar un reino.',
            'idreino.exists' => 'El reino seleccionado no es válido.'
        ]);

        Clase::create([
            'nombre' => $request->nombre,
            'definicion' => $request->definicion,
            'idreino' => $request->idreino
        ]);

        return redirect()->route('admin.clases.index')
            ->with('success', 'Clase creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Clase $clase)
    {
        $clase->load(['ordens.familias']);
        return view('admin.clases.show', compact('clase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clase $clase)
    {
        $reinos = \App\Models\Reino::orderBy('nombre')->get();
        return view('admin.clases.edit', compact('clase', 'reinos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clase $clase)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:clases,nombre,' . $clase->idclase . ',idclase',
            'definicion' => 'required|string|max:255',
            'idreino' => 'required|exists:reinos,id'
        ], [
            'nombre.required' => 'El nombre de la clase es obligatorio.',
            'nombre.unique' => 'Ya existe una clase con este nombre.',
            'definicion.required' => 'La definición es obligatoria.',
            'idreino.required' => 'Debe seleccionar un reino.',
            'idreino.exists' => 'El reino seleccionado no es válido.'
        ]);

        $clase->update([
            'nombre' => $request->nombre,
            'definicion' => $request->definicion,
            'idreino' => $request->idreino
        ]);

        return redirect()->route('admin.clases.index')
            ->with('success', 'Clase actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clase $clase)
    {
        // Verificar que no tenga órdenes asociados
        if ($clase->ordens()->count() > 0) {
            return redirect()->route('admin.clases.index')
                ->with('error', 'No se puede eliminar la clase porque tiene órdenes asociados.');
        }

        $clase->delete();

        return redirect()->route('admin.clases.index')
            ->with('success', 'Clase eliminada exitosamente.');
    }

    /**
     * Export clases to CSV
     */
    public function export()
    {
        $clases = Clase::withCount('ordens')->orderBy('nombre')->get();
        
        $filename = 'clases_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($clases) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'ID',
                'Nombre',
                'Definición',
                'Número de Órdenes',
                'Fecha de Creación'
            ]);
            
            // Data
            foreach ($clases as $clase) {
                fputcsv($file, [
                    $clase->idclase,
                    $clase->nombre,
                    $clase->definicion,
                    $clase->ordens_count,
                    $clase->created_at ? $clase->created_at->format('d/m/Y H:i:s') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
