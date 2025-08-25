<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BiodiversityCategory;
use Illuminate\Http\Request;

class BiodiversityCategoryController extends Controller
{
    /**
     * Mostrar una lista de todas las categorías de biodiversidad.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = BiodiversityCategory::query();

        // Aplicar filtros si existen
        if ($request->has('kingdom') && $request->kingdom) {
            $query->where('kingdom', $request->kingdom);
        }

        if ($request->has('conservation_status') && $request->conservation_status) {
            $query->where('conservation_status', $request->conservation_status);
        }

        if ($request->has('habitat') && $request->habitat) {
            $query->where('habitat', 'like', "%{$request->habitat}%");
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('scientific_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $biodiversityCategories = $query->simplePaginate(12);

        // Obtener opciones para los filtros
        $kingdoms = BiodiversityCategory::select('kingdom')->distinct()->pluck('kingdom');
        $conservationStatuses = BiodiversityCategory::select('conservation_status')->distinct()->pluck('conservation_status');
        $habitats = BiodiversityCategory::select('habitat')->distinct()->pluck('habitat');
        $totalBiodiversity = BiodiversityCategory::count();
        return view('frontend.biodiversity.index', compact('biodiversityCategories', 'kingdoms', 'conservationStatuses', 'habitats', 'totalBiodiversity'));
    }

    /**
     * Mostrar los detalles de una categoría de biodiversidad específica.
     *
     * @param  \App\Models\BiodiversityCategory  $biodiversityCategory
     * @return \Illuminate\Http\Response
     */
    public function show(BiodiversityCategory $biodiversityCategory)
    {
        // Cargar las publicaciones y estado de conservación relacionados
        $biodiversityCategory->load(['publications', 'conservationStatus']);

        // Obtener categorías similares (si el método existe)
        $similarBiodiversity = collect();
        if (method_exists($biodiversityCategory, 'getSimilarCategories')) {
            $similarBiodiversity = $biodiversityCategory->getSimilarCategories(5);
            // Cargar la relación conservationStatus para las categorías similares
            $similarBiodiversity->load('conservationStatus');
        } else {
            // Obtener especies similares por reino y estado de conservación
            $similarBiodiversity = BiodiversityCategory::where('id', '!=', $biodiversityCategory->id)
                ->where(function($query) use ($biodiversityCategory) {
                    $query->where('kingdom', $biodiversityCategory->kingdom)
                          ->orWhere('conservation_status', $biodiversityCategory->conservation_status);
                })
                ->with('conservationStatus')
                ->limit(5)
                ->get();
        }

        // Definir arrays de traducción
        $kingdoms = [
            'Animalia' => 'Animal',
            'Plantae' => 'Planta',
            'Fungi' => 'Hongo',
            'Protista' => 'Protista',
            'Bacteria' => 'Bacteria',
            'Archaea' => 'Archaea'
        ];

        $conservationStatuses = [
            'EX' => 'Extinto',
            'EW' => 'Extinto en Estado Silvestre',
            'CR' => 'En Peligro Crítico',
            'EN' => 'En Peligro',
            'VU' => 'Vulnerable',
            'NT' => 'Casi Amenazado',
            'LC' => 'Preocupación Menor',
            'DD' => 'Datos Insuficientes',
            'NE' => 'No Evaluado'
        ];

        return view('frontend.biodiversity.show', [
            'biodiversity' => $biodiversityCategory,
            'similarBiodiversity' => $similarBiodiversity,
            'kingdoms' => $kingdoms,
            'conservationStatuses' => $conservationStatuses
        ]);
    }
}