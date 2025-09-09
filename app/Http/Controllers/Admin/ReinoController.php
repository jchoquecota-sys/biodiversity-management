<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reinos = Reino::withCount('clases')->paginate(10);
        return view('admin.reinos.index', compact('reinos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.reinos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:reinos,nombre',
            'definicion' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Reino::create([
            'nombre' => $request->nombre,
            'definicion' => $request->definicion
        ]);

        return redirect()->route('admin.reinos.index')
            ->with('success', 'Reino creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reino $reino)
    {
        $reino->load(['clases.ordens.familias']);
        return view('admin.reinos.show', compact('reino'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reino $reino)
    {
        return view('admin.reinos.edit', compact('reino'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reino $reino)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:reinos,nombre,' . $reino->id,
            'definicion' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $reino->update([
            'nombre' => $request->nombre,
            'definicion' => $request->definicion
        ]);

        return redirect()->route('admin.reinos.index')
            ->with('success', 'Reino actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reino $reino)
    {
        // Verificar si el reino tiene clases asociadas
        if ($reino->clases()->count() > 0) {
            return redirect()->route('admin.reinos.index')
                ->with('error', 'No se puede eliminar el reino porque tiene clases asociadas.');
        }

        $reino->delete();

        return redirect()->route('admin.reinos.index')
            ->with('success', 'Reino eliminado exitosamente.');
    }

    /**
     * Export reinos to Excel.
     */
    public function export()
    {
        $reinos = Reino::withCount('clases')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reinos_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function() use ($reinos) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, ['ID', 'Nombre', 'Definición', 'Número de Clases', 'Fecha de Creación']);
            
            // Data
            foreach ($reinos as $reino) {
                fputcsv($file, [
                    $reino->id,
                    $reino->nombre,
                    $reino->definicion,
                    $reino->clases_count,
                    $reino->created_at ? \Carbon\Carbon::parse($reino->created_at)->format('d/m/Y H:i:s') : 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}