<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliderImages = HeroSliderImage::ordered()->get();
        return view('admin.hero-slider.index', compact('sliderImages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hero-slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'has_overlay_image' => 'boolean',
            'overlay_position' => 'nullable|string|in:left,right,center',
            'overlay_alt_text' => 'nullable|string|max:255',
            'overlay_description' => 'nullable|string',
            'overlay_button_text' => 'nullable|string|max:100',
            'overlay_button_url' => 'nullable|url|max:255',
            'overlay_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'overlay_width' => 'nullable|integer|min:50|max:800',
            'overlay_height' => 'nullable|integer|min:50|max:600'
        ]);

        $sliderImage = HeroSliderImage::create([
            'title' => $request->title,
            'description' => $request->description,
            'alt_text' => $request->alt_text,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'sort_order' => $request->sort_order,
            'is_active' => $request->has('is_active'),
            'has_overlay_image' => $request->has('has_overlay_image'),
            'overlay_position' => $request->overlay_position ?? 'left',
            'overlay_alt_text' => $request->overlay_alt_text,
            'overlay_description' => $request->overlay_description,
            'overlay_button_text' => $request->overlay_button_text,
            'overlay_button_url' => $request->overlay_button_url,
            'overlay_width' => $request->overlay_width ?? 300,
            'overlay_height' => $request->overlay_height ?? 200
        ]);

        if ($request->hasFile('image')) {
            $sliderImage->addMediaFromRequest('image')
                       ->toMediaCollection('hero_images');
        }
        
        if ($request->hasFile('overlay_image')) {
            $sliderImage->addMediaFromRequest('overlay_image')
                       ->toMediaCollection('overlay_images');
        }

        return redirect()->route('admin.hero-slider.index')
                        ->with('success', 'Imagen del slider creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HeroSliderImage $heroSlider)
    {
        return view('admin.hero-slider.show', compact('heroSlider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HeroSliderImage $heroSlider)
    {
        return view('admin.hero-slider.edit', compact('heroSlider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HeroSliderImage $heroSlider)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'has_overlay_image' => 'boolean',
            'overlay_position' => 'nullable|string|in:left,right,center',
            'overlay_alt_text' => 'nullable|string|max:255',
            'overlay_description' => 'nullable|string',
            'overlay_button_text' => 'nullable|string|max:100',
            'overlay_button_url' => 'nullable|url|max:255',
            'overlay_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $heroSlider->update([
            'title' => $request->title,
            'description' => $request->description,
            'alt_text' => $request->alt_text,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'sort_order' => $request->sort_order,
            'is_active' => $request->has('is_active'),
            'has_overlay_image' => $request->has('has_overlay_image'),
            'overlay_position' => $request->overlay_position ?? 'left',
            'overlay_alt_text' => $request->overlay_alt_text,
            'overlay_description' => $request->overlay_description,
            'overlay_button_text' => $request->overlay_button_text,
            'overlay_button_url' => $request->overlay_button_url
        ]);

        if ($request->hasFile('image')) {
            $heroSlider->clearMediaCollection('hero_images');
            $heroSlider->addMediaFromRequest('image')
                      ->toMediaCollection('hero_images');
        }
        
        if ($request->hasFile('overlay_image')) {
            $heroSlider->clearMediaCollection('overlay_images');
            $heroSlider->addMediaFromRequest('overlay_image')
                      ->toMediaCollection('overlay_images');
        }

        return redirect()->route('admin.hero-slider.index')
                        ->with('success', 'Imagen del slider actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeroSliderImage $heroSlider)
    {
        $heroSlider->clearMediaCollection('hero_images');
        $heroSlider->clearMediaCollection('overlay_images');
        $heroSlider->delete();

        return redirect()->route('admin.hero-slider.index')
                        ->with('success', 'Imagen del slider eliminada exitosamente.');
    }
}
