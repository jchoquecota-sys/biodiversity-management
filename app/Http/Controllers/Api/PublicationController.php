<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Publication::query();

        // Aplicar filtros si existen
        if ($request->has('year') && $request->year) {
            $query->where('publication_year', $request->year);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('abstract', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $publications = $query->paginate($request->per_page ?? 15);

        return response()->json($publications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'publication_year' => 'required|integer|min:1900|max:' . date('Y'),
            'author' => 'required|string|max:255',
            'journal' => 'nullable|string|max:255',
            'doi' => 'nullable|string|max:255',
        ]);

        $publication = Publication::create($request->all());

        return response()->json($publication, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication)
    {
        return response()->json($publication);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'publication_year' => 'required|integer|min:1900|max:' . date('Y'),
            'author' => 'required|string|max:255',
            'journal' => 'nullable|string|max:255',
            'doi' => 'nullable|string|max:255',
        ]);

        $publication->update($request->all());

        return response()->json($publication);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        $publication->delete();

        return response()->json(null, 204);
    }

    /**
     * Filter publications by various criteria.
     */
    public function filter(Request $request)
    {
        $query = Publication::query();

        // Filtrar por año
        if ($request->has('year_from') && $request->year_from) {
            $query->where('publication_year', '>=', $request->year_from);
        }

        if ($request->has('year_to') && $request->year_to) {
            $query->where('publication_year', '<=', $request->year_to);
        }

        // Filtrar por autor
        if ($request->has('author') && $request->author) {
            $query->where('author', 'like', "%{$request->author}%");
        }

        // Filtrar por revista
        if ($request->has('journal') && $request->journal) {
            $query->where('journal', 'like', "%{$request->journal}%");
        }

        // Filtrar por biodiversidad relacionada
        if ($request->has('biodiversity_id') && $request->biodiversity_id) {
            $query->whereHas('biodiversityCategories', function ($q) use ($request) {
                $q->where('biodiversity_categories.id', $request->biodiversity_id);
            });
        }

        // Búsqueda en título y resumen
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('abstract', 'like', "%{$search}%");
            });
        }

        $publications = $query->paginate($request->per_page ?? 15);

        return response()->json($publications);
    }
    
    /**
     * Get similar publications based on author, journal, or year.
     */
    public function similar(Publication $publication)
    {
        $similar = Publication::where('id', '!=', $publication->id)
            ->where(function($query) use ($publication) {
                $query->where('author', 'like', "%{$publication->author}%")
                      ->orWhere('journal', 'like', "%{$publication->journal}%")
                      ->orWhere('publication_year', $publication->publication_year);
            })
            ->limit(5)
            ->get();
            
        return response()->json($similar);
    }
}