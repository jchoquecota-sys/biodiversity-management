<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConservationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConservationStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conservationStatuses = ConservationStatus::orderBy('code')->paginate(10);
        
        return view('admin.conservation-status.index', compact('conservationStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.conservation-status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:2|unique:conservation_statuses,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        ConservationStatus::create($data);

        return redirect()->route('admin.conservation-status.index')
            ->with('success', 'Estado de conservación creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ConservationStatus $conservationStatus)
    {
        return view('admin.conservation-status.show', compact('conservationStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConservationStatus $conservationStatus)
    {
        return view('admin.conservation-status.edit', compact('conservationStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConservationStatus $conservationStatus)
    {
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                'string',
                'max:2',
                Rule::unique('conservation_statuses', 'code')->ignore($conservationStatus->id)
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $conservationStatus->update($data);

        return redirect()->route('admin.conservation-status.index')
            ->with('success', 'Estado de conservación actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConservationStatus $conservationStatus)
    {
        try {
            $conservationStatus->delete();
            return redirect()->route('admin.conservation-status.index')
                ->with('success', 'Estado de conservación eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.conservation-status.index')
                ->with('error', 'No se puede eliminar el estado de conservación porque está siendo utilizado.');
        }
    }

    /**
     * Export conservation statuses to Excel
     */
    public function export()
    {
        $conservationStatuses = ConservationStatus::orderBy('code')->get();
        
        $filename = 'estados_conservacion_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($conservationStatuses) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Código',
                'Nombre',
                'Descripción',
                'Color',
                'Activo',
                'Fecha de Creación'
            ]);
            
            // Data
            foreach ($conservationStatuses as $status) {
                fputcsv($file, [
                    $status->code,
                    $status->name,
                    $status->description,
                    $status->color,
                    $status->is_active ? 'Sí' : 'No',
                    $status->created_at->format('d/m/Y H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}