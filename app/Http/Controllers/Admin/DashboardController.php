<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\VisitCounterHelper;
use App\Http\Controllers\Controller;
use App\Models\BiodiversityCategory;
use App\Models\PageVisit;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Estadísticas de biodiversidad por categoría
        $biodiversityByKingdom = BiodiversityCategory::select('kingdom', DB::raw('count(*) as total'))
            ->groupBy('kingdom')
            ->get();

        // Gráficos de distribución por estado de conservación
        $biodiversityByConservationStatus = BiodiversityCategory::select('conservation_status', DB::raw('count(*) as total'))
            ->groupBy('conservation_status')
            ->get();

        // Últimas publicaciones agregadas
        $latestPublications = Publication::latest()->take(5)->get();

        // Total de especies y publicaciones
        $totalBiodiversity = BiodiversityCategory::count();
        $totalPublications = Publication::count();
        
        // Estadísticas de visitas
        $visitStats = VisitCounterHelper::getSiteStats();
        $topPages = VisitCounterHelper::getTopPages(10);
        
        // Visitas por día en los últimos 30 días
        $dailyVisits = PageVisit::selectRaw('DATE(created_at) as date, COUNT(*) as visits')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'biodiversityByKingdom',
            'biodiversityByConservationStatus',
            'latestPublications',
            'totalBiodiversity',
            'totalPublications',
            'visitStats',
            'topPages',
            'dailyVisits'
        ));
    }
}