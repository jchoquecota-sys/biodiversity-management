<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    /**
     * Muestra la página de configuración de menús y logo
     */
    public function index()
    {
        $menuSettings = \App\Models\Setting::getByGroup('menu');
        $logoSettings = \App\Models\Setting::getByGroup('logo');
        
        return view('admin.settings.site', compact('menuSettings', 'logoSettings'));
    }
    
    /**
     * Actualiza la configuración del logo
     */
    public function updateLogo(Request $request)
    {
        $validated = $request->validate([
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_logo_alt' => 'required|string|max:255',
        ]);
        
        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('logos', 'public');
            \App\Models\Setting::set('site_logo', $logoPath);
        }
        
        \App\Models\Setting::set('site_logo_alt', $validated['site_logo_alt']);
        
        return redirect()->route('admin.settings.site')
            ->with('success', 'Logo actualizado exitosamente');
    }
    
    /**
     * Actualiza la configuración de los menús
     */
    public function updateMenus(Request $request)
    {
        $validated = $request->validate([
            'menu_items' => 'required|array',
            'menu_items.*.text' => 'required|string|max:255',
            'menu_items.*.url' => 'required|string|max:255',
            'menu_items.*.order' => 'required|integer',
            'menu_items.*.parent_id' => 'nullable|integer',
            'menu_items.*.is_active' => 'boolean',
        ]);
        
        // Guardar los elementos del menú como JSON
        \App\Models\Setting::set('main_menu', json_encode($validated['menu_items']));
        
        return redirect()->route('admin.settings.site')
            ->with('success', 'Menús actualizados exitosamente');
    }
    
    /**
     * Inicializa las configuraciones por defecto
     */
    public function initializeDefaultSettings()
    {
        // Configuración del logo
        \App\Models\Setting::set('site_logo', 'logos/default-logo.svg');
        \App\Models\Setting::set('site_logo_alt', 'Biodiversidad');
        
        // Configuración del menú principal
        $defaultMenu = [
            [
                'text' => 'Inicio',
                'url' => '/',
                'order' => 1,
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'text' => 'Biodiversidad',
                'url' => '/biodiversity',
                'order' => 2,
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'text' => 'Publicaciones',
                'url' => '/publications',
                'order' => 3,
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'text' => 'Panel Admin',
                'url' => '/admin',
                'order' => 4,
                'parent_id' => null,
                'is_active' => true,
            ],
        ];
        
        \App\Models\Setting::set('main_menu', json_encode($defaultMenu));
        
        return redirect()->route('admin.settings.site')
            ->with('success', 'Configuraciones inicializadas exitosamente');
    }
}
