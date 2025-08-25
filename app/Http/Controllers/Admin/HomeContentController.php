<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = [
            'hero' => 'Sección Hero',
            'search' => 'Sección de Búsqueda',
            'stats' => 'Sección de Estadísticas',
            'featured' => 'Especies Destacadas',
            'publications' => 'Publicaciones',
            'cta' => 'Llamada a la Acción'
        ];
        
        $content = [];
        foreach ($sections as $section => $title) {
            $content[$section] = [
                'title' => $title,
                'items' => HomeContent::where('section', $section)->orderBy('sort_order')->get()
            ];
        }
        
        return view('admin.home-content.index', compact('content', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = [
            'hero' => 'Sección Hero',
            'search' => 'Sección de Búsqueda',
            'stats' => 'Sección de Estadísticas',
            'featured' => 'Especies Destacadas',
            'publications' => 'Publicaciones',
            'cta' => 'Llamada a la Acción'
        ];
        
        return view('admin.home-content.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|string',
            'key' => 'required|string',
            'value' => 'required|string',
            'type' => 'required|in:text,image,url',
            'sort_order' => 'integer|min:0'
        ]);
        
        $content = HomeContent::create($request->all());
        
        if ($request->hasFile('image') && $request->type === 'image') {
            $content->addMediaFromRequest('image')
                   ->toMediaCollection('images');
        }
        
        return redirect()->route('admin.home-content.index')
                        ->with('success', 'Contenido creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HomeContent $homeContent)
    {
        return view('admin.home-content.show', compact('homeContent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HomeContent $homeContent)
    {
        $sections = [
            'hero' => 'Sección Hero',
            'search' => 'Sección de Búsqueda',
            'stats' => 'Sección de Estadísticas',
            'featured' => 'Especies Destacadas',
            'publications' => 'Publicaciones',
            'cta' => 'Llamada a la Acción'
        ];
        
        return view('admin.home-content.edit', compact('homeContent', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HomeContent $homeContent)
    {
        $request->validate([
            'section' => 'required|string',
            'key' => 'required|string',
            'value' => 'required|string',
            'type' => 'required|in:text,image,url',
            'sort_order' => 'integer|min:0'
        ]);
        
        $homeContent->update($request->all());
        
        if ($request->hasFile('image') && $request->type === 'image') {
            $homeContent->clearMediaCollection('images');
            $homeContent->addMediaFromRequest('image')
                       ->toMediaCollection('images');
        }
        
        return redirect()->route('admin.home-content.index')
                        ->with('success', 'Contenido actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HomeContent $homeContent)
    {
        $homeContent->clearMediaCollection('images');
        $homeContent->delete();
        
        return redirect()->route('admin.home-content.index')
                        ->with('success', 'Contenido eliminado exitosamente.');
    }

    /**
     * Show hero slider configuration form
     */
    public function heroSliderConfig()
    {
        $useImageSlider = HomeContent::getContent('hero', 'use_image_slider', 'false');
        $sliderAutoplay = HomeContent::getContent('hero', 'slider_autoplay', 'true');
        $sliderInterval = HomeContent::getContent('hero', 'slider_interval', '5000');
        $enableIcons = HomeContent::getContent('hero', 'enable_icons', 'true');

        return view('admin.home-content.hero-slider-config', compact(
            'useImageSlider',
            'sliderAutoplay', 
            'sliderInterval',
            'enableIcons'
        ));
    }

    /**
     * Update hero slider configuration
     */
    public function updateHeroSliderConfig(Request $request)
    {
        $request->validate([
            'slider_interval' => 'required|integer|min:1000|max:10000'
        ]);

        // Update configurations
        HomeContent::setContent('hero', 'use_image_slider', $request->has('use_image_slider') ? 'true' : 'false');
        HomeContent::setContent('hero', 'slider_autoplay', $request->has('slider_autoplay') ? 'true' : 'false');
        HomeContent::setContent('hero', 'slider_interval', $request->slider_interval);
        HomeContent::setContent('hero', 'enable_icons', $request->has('enable_icons') ? 'true' : 'false');

        return redirect()->route('admin.hero-slider-config')
                        ->with('success', 'Configuración del slider actualizada exitosamente.');
    }
}
