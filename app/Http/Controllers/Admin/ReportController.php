<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiodiversityCategory;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Muestra la vista de estadísticas generales
     */
    public function generalStats()
    {
        // Estadísticas de biodiversidad
        $biodiversityStats = [
            'total' => BiodiversityCategory::count(),
            'by_kingdom' => BiodiversityCategory::select('kingdom', DB::raw('count(*) as total'))
                ->groupBy('kingdom')
                ->get(),
            'by_conservation' => BiodiversityCategory::select('conservation_status', DB::raw('count(*) as total'))
                ->groupBy('conservation_status')
                ->get(),
            'by_habitat' => BiodiversityCategory::select('habitat', DB::raw('count(*) as total'))
                ->groupBy('habitat')
                ->get()
        ];

        // Estadísticas de publicaciones
        $publicationStats = [
            'total' => Publication::count(),
            'by_year' => Publication::select('publication_year as year', DB::raw('count(*) as total'))
                ->groupBy('publication_year')
                ->orderBy('publication_year', 'desc')
                ->get(),
            'by_journal' => Publication::select('journal', DB::raw('count(*) as total'))
                ->groupBy('journal')
                ->orderBy('journal')
                ->get()
        ];

        return view('admin.reports.general', compact('biodiversityStats', 'publicationStats'));
    }

    /**
     * Genera un reporte PDF de biodiversidad
     */
    public function biodiversityIndex()
    {
        return view('admin.reports.biodiversity');
    }

    public function biodiversityData(Request $request)
    {
        $query = BiodiversityCategory::query();

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('common_name', function($row) {
                return $row->common_name ?? '-';
            })
            ->addColumn('family', function($row) {
                    return $row->familia_name ?? 'Sin familia asignada';
            })
            ->toJson();
    }

    public function biodiversityReport(Request $request)
    {
        $query = BiodiversityCategory::query();

        // Aplicar filtros si existen
        if ($request->kingdom) {
            $query->where('kingdom', $request->kingdom);
        }
        if ($request->conservation_status) {
            $query->where('conservation_status', $request->conservation_status);
        }

        $species = $query->get();

        $pdf = PDF::loadView('admin.reports.biodiversity_pdf', [
            'species' => $species,
            'filters' => $request->all(),
            'date' => now()->format('d/m/Y')
        ]);

        return $pdf->download('reporte-biodiversidad-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Muestra la vista de publicaciones
     */
    public function publicationsIndex()
    {
        return view('admin.reports.publications');
    }

    public function publicationsData(Request $request)
    {
        $query = Publication::query();

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('doi', function($row) {
                return $row->doi ?? '-';
            })
            ->toJson();
    }

    /**
     * Genera un reporte PDF de publicaciones
     */
    public function publicationsReport(Request $request)
    {
        $query = Publication::query();

        // Aplicar filtros si existen
        if ($request->year) {
            $query->where('publication_year', $request->year);
        }
        if ($request->journal) {
            $query->where('journal', $request->journal);
        }

        $publications = $query->get();

        // Estadísticas de publicaciones
        $publicationStats = [
            'total' => $publications->count(),
            'by_journal' => $publications->groupBy('journal')
                ->map(function ($group) {
                    return ['journal' => $group->first()->journal, 'total' => $group->count()];
                })
                ->values()
        ];

        // Generar PDF
        $pdf = PDF::loadView('admin.reports.publications_pdf', [
            'publications' => $publications,
            'publicationStats' => $publicationStats,
            'filters' => $request->all(),
            'date' => now()->format('d/m/Y')
        ]);

        return $pdf->download('reporte-publicaciones-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exporta datos de biodiversidad a Excel
     */
    public function exportBiodiversityExcel()
    {
        return Excel::download(new BiodiversityExport, 'biodiversidad-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Exporta datos de publicaciones a Excel
     */
    public function exportPublicationsExcel()
    {
        return Excel::download(new PublicationsExport, 'publicaciones-' . now()->format('Y-m-d') . '.xlsx');
    }
}