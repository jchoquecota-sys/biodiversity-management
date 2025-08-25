<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BiodiversityCategory;
use App\Models\Publication;
use App\Models\HomeContent;
use App\Models\HeroSliderImage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
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
                  ->orWhere('scientific_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $biodiversityCategories = $query->paginate(12);
      
        $kingdoms = BiodiversityCategory::select('kingdom')->distinct()->pluck('kingdom');
        $conservationStatuses = [
            'EX' => 'Extinto',
            'EW' => 'Extinto en Estado Silvestre',
            'CR' => 'En Peligro Crítico',
            'EN' => 'En Peligro',
            'VU' => 'Vulnerable',
            'NT' => 'Casi Amenazado',
            'LC' => 'Preocupación Menor',
            'DD' => 'Datos Insuficientes',
            'NE' => 'No Evaluado',
        ];

        // Statistics for dashboard
        $totalBiodiversity = BiodiversityCategory::count();
        $totalPublications = Publication::count();
        $endangeredCount = BiodiversityCategory::where('conservation_status', 'EN')->count();
        $criticallyEndangeredCount = BiodiversityCategory::where('conservation_status', 'CR')->count();
        
        // Featured biodiversity (latest 6)
        $featuredBiodiversity = BiodiversityCategory::latest()->take(6)->get();
        
        // Recent publications (latest 5)
        $recentPublications = Publication::latest()->take(5)->get();
        
        // Get dynamic content for all sections
        $heroContent = HomeContent::getSectionContent('hero');
        $searchContent = HomeContent::getSectionContent('search');
        $statsContent = HomeContent::getSectionContent('stats');
        $featuredContent = HomeContent::getSectionContent('featured');
        $publicationsContent = HomeContent::getSectionContent('publications');
        $ctaContent = HomeContent::getSectionContent('cta');
        
        // Get hero slider images with media relations
        $heroSliderImages = HeroSliderImage::active()->ordered()->with('media')->get();
        
        // Get hero configuration options
        $useImageSlider = HomeContent::getContent('hero', 'use_image_slider', 'false') === 'true';
        $sliderAutoplay = HomeContent::getContent('hero', 'slider_autoplay', 'true') === 'true';
        $sliderInterval = HomeContent::getContent('hero', 'slider_interval', '5000');
        $enableIcons = HomeContent::getContent('hero', 'enable_icons', 'true') === 'true';

        return view('frontend.home', compact(
            'biodiversityCategories', 
            'kingdoms', 
            'conservationStatuses',
            'totalBiodiversity',
            'totalPublications',
            'endangeredCount',
            'criticallyEndangeredCount',
            'featuredBiodiversity',
            'recentPublications',
            'heroContent',
            'searchContent',
            'statsContent',
            'featuredContent',
            'publicationsContent',
            'ctaContent',
            'heroSliderImages',
            'useImageSlider',
            'sliderAutoplay',
            'sliderInterval',
            'enableIcons'
        ));
    }

    /**
     * Display the biodiversity category details.
     */
    public function showBiodiversity(BiodiversityCategory $biodiversity)
    {
        $biodiversity->load('publications');
        return view('frontend.biodiversity.show', compact('biodiversity'));
    }

    /**
     * Display the publications page.
     */
    public function publications(Request $request)
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
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('journal', 'like', "%{$search}%");
            });
        }

        $publications = $query->paginate(10);
        $years = Publication::select('publication_year')->distinct()->orderBy('publication_year', 'desc')->pluck('publication_year');

        return view('frontend.publications.index', compact('publications', 'years'));
    }

    /**
     * Display the publication details.
     */
    public function showPublication(Publication $publication)
    {
        $publication->load('biodiversityCategories');
        return view('frontend.publications.show', compact('publication'));
    }
}