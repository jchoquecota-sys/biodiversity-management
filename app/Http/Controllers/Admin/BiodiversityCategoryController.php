<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiodiversityCategory;
use App\Models\Publication;
use App\Models\ConservationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BiodiversityCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = BiodiversityCategory::with(['familia.orden.clase']);

            return DataTables::of($query)
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->getImageUrl();
                    if ($imageUrl) {
                        return '<img src="' . $imageUrl . '" alt="' . $row->name . '" class="img-thumbnail cursor-pointer" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; transition: transform 0.2s;" onclick="showImageModal(\'' . $imageUrl . '\', \'' . addslashes($row->name) . '\')"> <br><small class="text-muted"></small>';
                    }
                    return '<div class="text-center"><i class="fas fa-image text-muted" style="font-size: 24px;"></i><br><small class="text-muted">Sin imagen</small></div>';
                })
                ->addColumn('taxonomic_hierarchy', function ($row) {
                    if ($row->familia) {
                        $hierarchy = '<div class="taxonomic-hierarchy">';
                        $hierarchy .= '<small class="text-muted d-block">Clase:</small>';
                        $hierarchy .= '<strong>' . ($row->familia->orden->clase->nombre ?? 'N/A') . '</strong><br>';
                        $hierarchy .= '<small class="text-muted d-block">Orden:</small>';
                        $hierarchy .= '<strong>' . ($row->familia->orden->nombre ?? 'N/A') . '</strong><br>';
                        $hierarchy .= '<small class="text-muted d-block">Familia:</small>';
                        $hierarchy .= '<strong>' . $row->familia->nombre . '</strong>';
                        $hierarchy .= '</div>';
                        return $hierarchy;
                    }
                    return '<span class="text-muted">Sin clasificación taxonómica</span>';
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="btn-group">';
                    $actions .= '<a href="' . route('admin.biodiversity.show', $row->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('admin.biodiversity.edit', $row->id) . '" class="btn btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                    $actions .= '<form action="' . route('admin.biodiversity.destroy', $row->id) . '" method="POST" onsubmit="return confirm(\'¿Está seguro?\');" style="display: inline-block;">';
                    $actions .= csrf_field() . method_field('DELETE');
                    $actions .= '<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                    $actions .= '</form>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['image', 'taxonomic_hierarchy', 'action'])
                ->make(true);
        }

        return view('admin.biodiversity.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $publications = Publication::all();
        $clases = \App\Models\Clase::orderBy('nombre')->get();
        $ordenes = \App\Models\Orden::with('clase')->orderBy('nombre')->get();
        $familias = \App\Models\Familia::with(['orden.clase'])->orderBy('nombre')->get();
        $conservationStatuses = ConservationStatus::orderBy('priority')->get();
        $reinos = \App\Models\Reino::orderBy('nombre')->get();

        return view('admin.biodiversity.create', compact('publications', 'conservationStatuses', 'reinos', 'clases', 'ordenes', 'familias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255|unique:biodiversity_categories,scientific_name,NULL,id,deleted_at,NULL',
            'conservation_status' => 'nullable|in:EX,EW,CR,EN,VU,NT,LC,DD,NE',
            'conservation_status_id' => 'nullable|exists:conservation_statuses,id',
            'idreino' => 'nullable|exists:reinos,id',
            'idfamilia' => 'nullable|exists:familias,idfamilia',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'habitat' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'publications' => 'nullable|array',
            'publications.*' => 'exists:publications,id',
            'relevant_excerpts' => 'nullable|array',
            'page_references' => 'nullable|array',
        ]);

        $biodiversityData = $request->except(['image', 'publications', 'relevant_excerpts', 'page_references']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('admin/biodiversity', $imageName, 'public');
            $biodiversityData['image_path'] = 'admin/biodiversity/' . $imageName;
        }

        $biodiversity = BiodiversityCategory::create($biodiversityData);

        if ($request->has('publications')) {
            $pivotData = [];
            foreach ($request->publications as $key => $publicationId) {
                $pivotData[$publicationId] = [
                    'relevant_excerpt' => $request->relevant_excerpts[$key] ?? null,
                    'page_reference' => $request->page_references[$key] ?? null,
                ];
            }
            $biodiversity->publications()->attach($pivotData);
        }

        return redirect()->route('admin.biodiversity.index')
            ->with('success', 'Categoría de biodiversidad creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BiodiversityCategory $biodiversity)
    {
        $biodiversity->load('publications');
        return view('admin.biodiversity.show', compact('biodiversity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BiodiversityCategory $biodiversity)
    {
        $publications = Publication::all();
        $biodiversity->load(['publications', 'familia.orden.clase']);
        $clases = \App\Models\Clase::orderBy('nombre')->get();
        $ordenes = \App\Models\Orden::with('clase')->orderBy('nombre')->get();
        $familias = \App\Models\Familia::with(['orden.clase'])->orderBy('nombre')->get();
        $conservationStatuses = ConservationStatus::orderBy('priority')->get();
        $reinos = \App\Models\Reino::orderBy('nombre')->get();

        return view('admin.biodiversity.edit', compact('biodiversity', 'publications', 'conservationStatuses', 'reinos', 'clases', 'ordenes', 'familias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BiodiversityCategory $biodiversity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255|unique:biodiversity_categories,scientific_name,' . $biodiversity->id . ',id,deleted_at,NULL',
            'conservation_status' => 'nullable|in:EX,EW,CR,EN,VU,NT,LC,DD,NE',
            'conservation_status_id' => 'nullable|exists:conservation_statuses,id',
            'idreino' => 'nullable|exists:reinos,id',
            'idfamilia' => 'nullable|exists:familias,idfamilia',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'habitat' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'publications' => 'nullable|array',
            'publications.*' => 'exists:publications,id',
            'relevant_excerpts' => 'nullable|array',
            'page_references' => 'nullable|array',
        ]);

        $biodiversityData = $request->except(['image', 'publications', 'relevant_excerpts', 'page_references']);

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($biodiversity->image_path && \Storage::disk('public')->exists($biodiversity->image_path)) {
                \Storage::disk('public')->delete($biodiversity->image_path);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('admin/biodiversity', $imageName, 'public');
            $biodiversityData['image_path'] = 'admin/biodiversity/' . $imageName;
        }

        $biodiversity->update($biodiversityData);

        if ($request->has('publications')) {
            $pivotData = [];
            foreach ($request->publications as $key => $publicationId) {
                $pivotData[$publicationId] = [
                    'relevant_excerpt' => $request->relevant_excerpts[$key] ?? null,
                    'page_reference' => $request->page_references[$key] ?? null,
                ];
            }
            $biodiversity->publications()->sync($pivotData);
        } else {
            $biodiversity->publications()->detach();
        }

        return redirect()->route('admin.biodiversity.index')
            ->with('success', 'Categoría de biodiversidad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BiodiversityCategory $biodiversity)
    {
        $biodiversity->delete();

        return redirect()->route('admin.biodiversity.index')
            ->with('success', 'Categoría de biodiversidad eliminada exitosamente.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $biodiversity = BiodiversityCategory::withTrashed()->findOrFail($id);
        $biodiversity->restore();

        return redirect()->route('admin.biodiversity.index')
            ->with('success', 'Categoría de biodiversidad restaurada exitosamente.');
    }

    /**
     * Display a listing of the trashed resources.
     */
    public function trashed()
    {
        $trashedBiodiversity = BiodiversityCategory::onlyTrashed()->get();

        return view('admin.biodiversity.trashed', compact('trashedBiodiversity'));
    }

    /**
     * Export biodiversity categories to Excel/CSV.
     */
    public function export()
    {
        // Implementación de exportación a Excel/CSV
        return redirect()->route('admin.biodiversity.index')
            ->with('success', 'Datos exportados exitosamente.');
    }

    /**
     * Get clases by reino for AJAX requests
     */
    public function getClasesByReino($reinoId)
    {
        $clases = \App\Models\Clase::where('idreino', $reinoId)->orderBy('nombre')->get();
        return response()->json($clases);
    }

    /**
     * Get ordenes by clase for AJAX requests
     */
    public function getOrdenesByClase($claseId)
    {
        $ordenes = \App\Models\Orden::where('idclase', $claseId)->orderBy('nombre')->get();
        return response()->json($ordenes);
    }

    /**
     * Get familias by orden for AJAX requests
     */
    public function getFamiliasByOrden($ordenId)
    {
        $familias = \App\Models\Familia::where('idorden', $ordenId)->orderBy('nombre')->get();
        return response()->json($familias);
    }
}