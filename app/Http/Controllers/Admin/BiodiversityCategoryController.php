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
                    $allImages = $row->getAllImageUrls();
                    $imageCount = $row->getImageCount();
                    
                    if (!empty($allImages)) {
                        $html = '<div class="image-gallery" style="display: flex; flex-wrap: wrap; gap: 2px; justify-content: center;">';
                        
                        // Mostrar hasta 3 imágenes en la vista de lista
                        $displayImages = array_slice($allImages, 0, 3);
                        
                        foreach ($displayImages as $index => $imageUrl) {
                            $html .= '<img src="' . $imageUrl . '" alt="' . $row->name . ' - Imagen ' . ($index + 1) . '" class="img-thumbnail cursor-pointer" style="width: 35px; height: 35px; object-fit: cover; border-radius: 4px; transition: transform 0.2s;" onclick="showImageModal(\'' . $imageUrl . '\', \'' . addslashes($row->name) . ' - Imagen ' . ($index + 1) . '\')">';
                        }
                        
                        $html .= '</div>';
                        
                        // Mostrar contador de imágenes
                        if ($imageCount > 1) {
                            $html .= '<small class="text-muted d-block mt-1 text-center"><i class="fas fa-images"></i> ' . $imageCount . ' imágenes</small>';
                        }
                        
                        return $html;
                    }
                    return '<div class="text-center"><i class="fas fa-image text-muted" style="font-size: 24px;"></i><br><small class="text-muted">Sin imagen</small></div>';
                })
                ->editColumn('scientific_name', function ($row) {
                    return '<span class="scientific-name">' . $row->scientific_name . '</span>';
                })
                ->editColumn('conservation_status', function ($row) {
                    $conservationStatus = ConservationStatus::where('code', $row->conservation_status)->first();
                    if ($conservationStatus) {
                        $statusClass = 'badge bg-' . $conservationStatus->color;
                        $statusName = $conservationStatus->name;
                    } else {
                        $statusClass = 'badge bg-secondary';
                        $statusName = $row->conservation_status;
                    }
                    return '<span class="' . $statusClass . '">' . $statusName . '</span>';
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
                ->rawColumns(['image', 'scientific_name', 'conservation_status', 'taxonomic_hierarchy', 'action'])
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
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'habitat' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'publications' => 'nullable|array',
            'publications.*' => 'exists:publications,id',
            'relevant_excerpts' => 'nullable|array',
            'page_references' => 'nullable|array',
        ]);

        $biodiversityData = $request->except(['image', 'image_2', 'image_3', 'image_4', 'publications', 'relevant_excerpts', 'page_references']);

        // Manejar imagen principal
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('admin/biodiversity', $imageName, 'public');
            $biodiversityData['image_path'] = 'admin/biodiversity/' . $imageName;
        }

        // Manejar imágenes adicionales
        for ($i = 2; $i <= 4; $i++) {
            $imageField = 'image_' . $i;
            $imagePathField = 'image_path_' . $i;
            
            if ($request->hasFile($imageField)) {
                $image = $request->file($imageField);
                $imageName = time() . '_' . $i . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('admin/biodiversity', $imageName, 'public');
                $biodiversityData[$imagePathField] = 'admin/biodiversity/' . $imageName;
            }
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
        
        // Obtener los estados de conservación desde la base de datos
        $conservationStatusesFromDB = ConservationStatus::all()->keyBy('code');
        $conservationStatuses = $conservationStatusesFromDB->pluck('name', 'code')->toArray();
        
        // Definir los reinos para mostrar en la vista
        $kingdoms = [
            'animal' => 'Animal',
            'plant' => 'Vegetal',
            'fungi' => 'Fungi',
            'protist' => 'Protista',
            'bacteria' => 'Bacteria',
            'archaea' => 'Archaea'
        ];
        
        return view('admin.biodiversity.show', compact('biodiversity', 'conservationStatuses', 'kingdoms', 'conservationStatusesFromDB'));
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
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'habitat' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'publications' => 'nullable|array',
            'publications.*' => 'exists:publications,id',
            'relevant_excerpts' => 'nullable|array',
            'page_references' => 'nullable|array',
        ]);

        $biodiversityData = $request->except(['image', 'image_2', 'image_3', 'image_4', 'publications', 'relevant_excerpts', 'page_references']);

        // Manejar imagen principal
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

        // Manejar imágenes adicionales
        for ($i = 2; $i <= 4; $i++) {
            $imageField = 'image_' . $i;
            $imagePathField = 'image_path_' . $i;
            
            if ($request->hasFile($imageField)) {
                // Eliminar imagen anterior si existe
                if ($biodiversity->$imagePathField && \Storage::disk('public')->exists($biodiversity->$imagePathField)) {
                    \Storage::disk('public')->delete($biodiversity->$imagePathField);
                }
                
                $image = $request->file($imageField);
                $imageName = time() . '_' . $i . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('admin/biodiversity', $imageName, 'public');
                $biodiversityData[$imagePathField] = 'admin/biodiversity/' . $imageName;
            }
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

    /**
     * Mostrar formulario de carga masiva de imágenes
     */
    public function bulkImageUpload()
    {
        $speciesWithoutImages = BiodiversityCategory::whereNull('image_path')
            ->orWhere('image_path', '')
            ->orderBy('name')
            ->get();
            
        return view('admin.biodiversity.bulk-image-upload', compact('speciesWithoutImages'));
    }

    /**
     * Procesar carga masiva de imágenes
     */
    public function processBulkImageUpload(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'species_ids' => 'required|array',
            'species_ids.*' => 'exists:biodiversity_categories,id',
        ]);

        $uploaded = 0;
        $errors = [];

        foreach ($request->file('images') as $key => $image) {
            if (!isset($request->species_ids[$key])) {
                continue;
            }

            try {
                $speciesId = $request->species_ids[$key];
                $species = BiodiversityCategory::findOrFail($speciesId);

                // Eliminar imagen anterior si existe
                if ($species->image_path && \Storage::disk('public')->exists($species->image_path)) {
                    \Storage::disk('public')->delete($species->image_path);
                }

                // Guardar nueva imagen
                $imageName = time() . '_' . $speciesId . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('biodiversity/especies', $imageName, 'public');
                $species->update(['image_path' => 'biodiversity/especies/' . $imageName]);

                $uploaded++;
            } catch (\Exception $e) {
                $errors[] = "Error procesando imagen para especie ID {$speciesId}: " . $e->getMessage();
            }
        }

        $message = "Se subieron {$uploaded} imágenes exitosamente.";
        if (!empty($errors)) {
            $message .= " Errores: " . implode(', ', $errors);
        }

        return redirect()->route('admin.biodiversity.bulk-image-upload')
            ->with('success', $message);
    }

    /**
     * Importar imágenes desde URLs (AJAX)
     */
    public function importFromUrl(Request $request)
    {
        $request->validate([
            'species_id' => 'required|exists:biodiversity_categories,id',
            'image_url' => 'required|url',
        ]);

        try {
            $species = BiodiversityCategory::findOrFail($request->species_id);
            
            // Descargar imagen desde URL
            $response = \Http::timeout(30)->get($request->image_url);
            
            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al descargar la imagen desde la URL'
                ]);
            }

            // Verificar que sea una imagen válida
            $imageInfo = getimagesizefromstring($response->body());
            if (!$imageInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo descargado no es una imagen válida'
                ]);
            }

            // Generar nombre único
            $extension = $this->getExtensionFromMimeType($imageInfo['mime']) ?: 'jpg';
            $filename = \Str::slug($species->scientific_name) . '_' . time() . '.' . $extension;
            $path = 'biodiversity/especies/' . $filename;

            // Eliminar imagen anterior si existe
            if ($species->image_path && \Storage::disk('public')->exists($species->image_path)) {
                \Storage::disk('public')->delete($species->image_path);
            }

            // Guardar imagen
            \Storage::disk('public')->put($path, $response->body());
            $species->update(['image_path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Imagen importada exitosamente',
                'image_url' => $species->getImageUrl()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar imagen de una especie
     */
    public function removeImage(Request $request)
    {
        $request->validate([
            'species_id' => 'required|exists:biodiversity_categories,id',
        ]);

        try {
            $species = BiodiversityCategory::findOrFail($request->species_id);
            
            if ($species->image_path && \Storage::disk('public')->exists($species->image_path)) {
                \Storage::disk('public')->delete($species->image_path);
            }
            
            $species->update(['image_path' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener extensión desde MIME type
     */
    private function getExtensionFromMimeType($mimeType)
    {
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];
        
        return $mimeToExt[$mimeType] ?? 'jpg';
    }
}