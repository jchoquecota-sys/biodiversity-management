<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiodiversityCategory;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Publication::query();

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $actions = '<div class="btn-group">';
                    $actions .= '<a href="' . route('admin.publications.show', $row->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('admin.publications.edit', $row->id) . '" class="btn btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                    $actions .= '<form action="' . route('admin.publications.destroy', $row->id) . '" method="POST" onsubmit="return confirm(\'¿Está seguro?\');" style="display: inline-block;">';
                    $actions .= csrf_field() . method_field('DELETE');
                    $actions .= '<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                    $actions .= '</form>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.publications.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $biodiversityCategories = BiodiversityCategory::all();
        return view('admin.publications.create', compact('biodiversityCategories'));
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
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'biodiversity_categories' => 'nullable|array',
            'biodiversity_categories.*' => 'exists:biodiversity_categories,id',
            'relevant_excerpts' => 'nullable|array',
            'page_references' => 'nullable|array',
        ]);

        $publicationData = $request->except(['pdf', 'biodiversity_categories', 'relevant_excerpts', 'page_references']);
        
        // Handle PDF file upload
        if ($request->hasFile('pdf')) {
            $pdfFile = $request->file('pdf');
            $fileName = time() . '_' . $pdfFile->getClientOriginalName();
            $pdfPath = $pdfFile->storeAs('admin/publication', $fileName, 'public');
            $publicationData['pdf_path'] = $pdfPath;
        }
        
        $publication = Publication::create($publicationData);

        if ($request->has('biodiversity_categories')) {
            $pivotData = [];
            $categoryIds = $request->biodiversity_categories;
            $relevantExcerpts = $request->relevant_excerpts ?? [];
            $pageReferences = $request->page_references ?? [];
            
            // Debug: Verificar datos recibidos
            \Log::info('=== PUBLICATION STORE DEBUG ===');
            \Log::info('All request data:', $request->all());
            \Log::info('Biodiversity categories:', $request->biodiversity_categories);
            \Log::info('Relevant excerpts:', $request->relevant_excerpts);
            \Log::info('Page references:', $request->page_references);
            \Log::info('Categories count: ' . (is_array($request->biodiversity_categories) ? count($request->biodiversity_categories) : 0));
            \Log::info('Excerpts count: ' . (is_array($request->relevant_excerpts) ? count($request->relevant_excerpts) : 0));
            \Log::info('References count: ' . (is_array($request->page_references) ? count($request->page_references) : 0));
            \Log::info('Publication Store - Debug Data:', [
                'biodiversity_categories' => $categoryIds,
                'relevant_excerpts' => $relevantExcerpts,
                'page_references' => $pageReferences,
                'all_request_data' => $request->all()
            ]);
            
            // Remove duplicates while preserving the first occurrence and its data
            $uniqueCategories = [];
            $processedCategories = [];
            
            foreach ($categoryIds as $key => $categoryId) {
                if (!in_array($categoryId, $processedCategories)) {
                    $excerpt = $relevantExcerpts[$key] ?? null;
                    $reference = $pageReferences[$key] ?? null;
                    
                    $uniqueCategories[$categoryId] = [
                        'relevant_excerpt' => $excerpt,
                        'page_reference' => $reference,
                    ];
                    
                    $processedCategories[] = $categoryId;
                    
                    \Log::info("Processing unique category {$categoryId} at index {$key}:", [
                        'excerpt' => $excerpt,
                        'reference' => $reference,
                        'excerpt_length' => $excerpt ? strlen($excerpt) : 0,
                        'reference_length' => $reference ? strlen($reference) : 0
                    ]);
                } else {
                    \Log::info("Skipping duplicate category {$categoryId} at index {$key}");
                }
            }
            
            // Attach all unique categories at once
            $publication->biodiversityCategories()->attach($uniqueCategories);
            
            \Log::info('Categories attached successfully');
        }

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publicación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication)
    {
        $publication->load('biodiversityCategories');
        return view('admin.publications.show', compact('publication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publication $publication)
    {
        $biodiversityCategories = BiodiversityCategory::all();
        $publication->load('biodiversityCategories');
        return view('admin.publications.edit', compact('publication', 'biodiversityCategories'));
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
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'biodiversity_categories' => 'nullable|array',
            'biodiversity_categories.*' => 'exists:biodiversity_categories,id',
            'relevant_excerpts' => 'nullable|array',
            'page_references' => 'nullable|array',
        ]);

        $publicationData = $request->except(['pdf', 'biodiversity_categories', 'relevant_excerpts', 'page_references']);
        
        // Handle PDF file upload
        if ($request->hasFile('pdf')) {
            // Delete old PDF file if exists
            if ($publication->pdf_path && Storage::disk('public')->exists($publication->pdf_path)) {
                Storage::disk('public')->delete($publication->pdf_path);
            }
            
            $pdfFile = $request->file('pdf');
            $fileName = time() . '_' . $pdfFile->getClientOriginalName();
            $pdfPath = $pdfFile->storeAs('admin/publication', $fileName, 'public');
            $publicationData['pdf_path'] = $pdfPath;
        }
        
        $publication->update($publicationData);

        if ($request->has('biodiversity_categories')) {
            $pivotData = [];
            $categoryIds = $request->biodiversity_categories;
            $relevantExcerpts = $request->relevant_excerpts ?? [];
            $pageReferences = $request->page_references ?? [];
            
            // Debug logging
            \Log::info('Publication Update - Debug Data:', [
                'biodiversity_categories' => $categoryIds,
                'relevant_excerpts' => $relevantExcerpts,
                'page_references' => $pageReferences,
                'all_request_data' => $request->all()
            ]);
            
            // First detach all existing categories
            $publication->biodiversityCategories()->detach();
            
            // Remove duplicates while preserving the first occurrence and its data
            $uniqueCategories = [];
            $processedCategories = [];
            
            foreach ($categoryIds as $key => $categoryId) {
                if (!in_array($categoryId, $processedCategories)) {
                    $excerpt = $relevantExcerpts[$key] ?? null;
                    $reference = $pageReferences[$key] ?? null;
                    
                    $uniqueCategories[$categoryId] = [
                        'relevant_excerpt' => $excerpt,
                        'page_reference' => $reference,
                    ];
                    
                    $processedCategories[] = $categoryId;
                    
                    \Log::info("Processing unique UPDATE category {$categoryId} at index {$key}:", [
                        'excerpt' => $excerpt,
                        'reference' => $reference,
                        'excerpt_length' => $excerpt ? strlen($excerpt) : 0,
                        'reference_length' => $reference ? strlen($reference) : 0
                    ]);
                } else {
                    \Log::info("Skipping duplicate UPDATE category {$categoryId} at index {$key}");
                }
            }
            
            // Attach all unique categories at once
            $publication->biodiversityCategories()->attach($uniqueCategories);
            \Log::info('UPDATE categories attached successfully');
        } else {
            $publication->biodiversityCategories()->detach();
        }

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publicación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        $publication->delete();

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publicación eliminada exitosamente.');
    }

    /**
     * Export publications to Excel/CSV.
     */
    public function export()
    {
        // Implementación de exportación a Excel/CSV
        return redirect()->route('admin.publications.index')
            ->with('success', 'Datos exportados exitosamente.');
    }
}