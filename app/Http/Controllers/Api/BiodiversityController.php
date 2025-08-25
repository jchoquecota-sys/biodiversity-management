<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiodiversityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiodiversityController extends Controller
{
    /**
     * Display a listing of the resource.
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

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('scientific_name', 'like', "%{$search}%");
            });
        }

        $biodiversityCategories = $query->paginate($request->per_page ?? 15);

        return response()->json($biodiversityCategories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255|unique:biodiversity_categories,scientific_name,NULL,id,deleted_at,NULL',
            'conservation_status' => 'required|in:EX,EW,CR,EN,VU,NT,LC,DD,NE',
            'kingdom' => 'required|string|max:255',
            'habitat' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $biodiversity = BiodiversityCategory::create($request->all());

        return response()->json($biodiversity, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BiodiversityCategory $biodiversity)
    {
        return response()->json($biodiversity);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BiodiversityCategory $biodiversity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255|unique:biodiversity_categories,scientific_name,' . $biodiversity->id . ',id,deleted_at,NULL',
            'conservation_status' => 'required|in:EX,EW,CR,EN,VU,NT,LC,DD,NE',
            'kingdom' => 'required|string|max:255',
            'habitat' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $biodiversity->update($request->all());

        return response()->json($biodiversity);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BiodiversityCategory $biodiversity)
    {
        $biodiversity->delete();

        return response()->json(null, 204);
    }

    /**
     * Get publications related to a biodiversity category.
     */
    public function publications(BiodiversityCategory $biodiversity)
    {
        $publications = $biodiversity->publications;

        return response()->json($publications);
    }
    
    /**
     * Get similar biodiversity categories based on kingdom and conservation status.
     */
    public function similar(BiodiversityCategory $biodiversity)
    {
        $similar = BiodiversityCategory::where('id', '!=', $biodiversity->id)
            ->where(function($query) use ($biodiversity) {
                $query->where('kingdom', $biodiversity->kingdom)
                      ->orWhere('conservation_status', $biodiversity->conservation_status);
            })
            ->limit(5)
            ->get();
            
        return response()->json($similar);
    }
}