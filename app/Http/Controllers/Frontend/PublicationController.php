<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\BiodiversityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    /**
     * Mostrar una lista de todas las publicaciones.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Publication::query();

        // Aplicar filtros si existen
        if ($request->has('year') && $request->year) {
            $query->where('publication_year', $request->year);
        }

        if ($request->has('author') && $request->author) {
            $query->where('author', 'like', "%{$request->author}%");
        }

        if ($request->has('journal') && $request->journal) {
            $query->where('journal', 'like', "%{$request->journal}%");
        }

        if ($request->has('biodiversity') && $request->biodiversity) {
            $query->whereHas('biodiversityCategories', function ($q) use ($request) {
                $q->where('biodiversity_categories.id', $request->biodiversity);
            });
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('abstract', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('journal', 'like', "%{$search}%");
            });
        }

        $publications = $query->with('biodiversityCategories')->paginate(10);

        // Obtener opciones para los filtros
        $years = Publication::select('publication_year')->distinct()->orderBy('publication_year', 'desc')->pluck('publication_year');
        $authors = Publication::select('author')->distinct()->pluck('author');
        $journals = Publication::select('journal')->distinct()->pluck('journal');
        $biodiversityCategories = BiodiversityCategory::orderBy('name')->get();
        $totalBiodiversity = 5;
        return view('frontend.publications.index', compact('publications', 'years', 'authors', 'journals', 'biodiversityCategories', 'totalBiodiversity'));
    }

    /**
     * Mostrar los detalles de una publicación específica.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function show(Publication $publication)
    {
        // Cargar las categorías de biodiversidad relacionadas
        $publication->load('biodiversityCategories');

        // Obtener publicaciones similares (si el método existe)
        $similarPublications = collect();
        if (method_exists($publication, 'getSimilarPublications')) {
            $similarPublications = $publication->getSimilarPublications(5);
        } else {
            // Obtener publicaciones similares por autor o año
            $similarPublications = Publication::where('id', '!=', $publication->id)
                ->where(function($query) use ($publication) {
                    $query->where('author', $publication->author)
                          ->orWhere('publication_year', $publication->publication_year)
                          ->orWhere('journal', $publication->journal);
                })
                ->limit(5)
                ->get();
        }

        return view('frontend.publications.show', [
            'publication' => $publication,
            'similarPublications' => $similarPublications
        ]);
    }

    /**
     * Descargar el PDF de una publicación.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Publication $publication)
    {
        if (!$publication->pdf_path) {
            return redirect()->back()->with('error', 'No se encontró el archivo PDF para esta publicación.');
        }
        
        $filePath = null;
        $fileName = $publication->title . '.pdf';
        
        // Verificar si el archivo está en storage/app/public/
        if (Storage::disk('public')->exists($publication->pdf_path)) {
            $filePath = Storage::disk('public')->path($publication->pdf_path);
        }
        // Verificar si el archivo está en public/estudios/
        elseif (file_exists(public_path('estudios/' . basename($publication->pdf_path)))) {
            $filePath = public_path('estudios/' . basename($publication->pdf_path));
        }
        // Verificar si el archivo está directamente en public/
        elseif (file_exists(public_path($publication->pdf_path))) {
            $filePath = public_path($publication->pdf_path);
        }
        
        if (!$filePath || !file_exists($filePath)) {
            return redirect()->back()->with('error', 'No se encontró el archivo PDF para esta publicación.');
        }
        
        return response()->download($filePath, $fileName);
    }
}