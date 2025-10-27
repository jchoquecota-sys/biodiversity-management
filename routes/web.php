<?php

use App\Http\Controllers\Admin\BiodiversityCategoryController;
use App\Http\Controllers\Admin\ClaseController;
use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\Admin\ConservationStatusController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Admin\FamiliaController;
use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Admin\OrdenController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\ReinoController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BiodiversityCategoryController as FrontendBiodiversityCategoryController;
use App\Http\Controllers\Frontend\PublicationController as FrontendPublicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Biodiversity Routes
Route::get('/biodiversity', [FrontendBiodiversityCategoryController::class, 'index'])->name('biodiversity.index');
Route::get('/biodiversity/{biodiversityCategory}', [FrontendBiodiversityCategoryController::class, 'show'])->name('biodiversity.show');

// Publications Routes
Route::get('/publications', [FrontendPublicationController::class, 'index'])->name('publications.index');
Route::get('/publications/{publication}', [FrontendPublicationController::class, 'show'])->name('publications.show');
Route::get('/publications/{publication}/download', [FrontendPublicationController::class, 'downloadPdf'])->name('publications.download');

// Admin Routes
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Biodiversity Categories
    Route::get('biodiversity/trashed', [BiodiversityCategoryController::class, 'trashed'])->name('biodiversity.trashed');
    Route::post('biodiversity/{id}/restore', [BiodiversityCategoryController::class, 'restore'])->name('biodiversity.restore');
    Route::get('biodiversity/export', [BiodiversityCategoryController::class, 'export'])->name('biodiversity.export');
    Route::resource('biodiversity', BiodiversityCategoryController::class);
    
    // API Routes for cascading selects
    Route::get('api/clases/reino/{reinoId}', [BiodiversityCategoryController::class, 'getClasesByReino'])->name('api.clases.by-reino');
    Route::get('api/ordenes/clase/{claseId}', [BiodiversityCategoryController::class, 'getOrdenesByClase'])->name('api.ordenes.by-clase');
    Route::get('api/familias/orden/{ordenId}', [BiodiversityCategoryController::class, 'getFamiliasByOrden'])->name('api.familias.by-orden');
    
    // Publications
    Route::get('publications/export', [PublicationController::class, 'export'])->name('publications.export');
    Route::resource('publications', PublicationController::class);
    
    // Conservation Status
    Route::get('conservation-status/export', [ConservationStatusController::class, 'export'])->name('conservation-status.export');
    Route::resource('conservation-status', ConservationStatusController::class);
    
    // Taxonomic Classification
    Route::get('reinos/export', [ReinoController::class, 'export'])->name('reinos.export');
    Route::resource('reinos', ReinoController::class);
    
    Route::get('clases/export', [ClaseController::class, 'export'])->name('clases.export');
    Route::resource('clases', ClaseController::class);
    
    Route::get('ordens/export', [OrdenController::class, 'export'])->name('ordens.export');
    Route::resource('ordens', OrdenController::class);
    
    Route::get('familias/export', [FamiliaController::class, 'export'])->name('familias.export');
    Route::resource('familias', FamiliaController::class);
    
    // Session Debug Route
    Route::get('session-debug', function () {
        return view('session-debug');
    })->name('session.debug');
    
    // Home Content Management
    Route::resource('home-content', HomeContentController::class);
    
    // Hero Slider Management
    Route::resource('hero-slider', HeroSliderController::class);
    
    // Hero Slider Configuration
    Route::get('hero-slider-config', [HomeContentController::class, 'heroSliderConfig'])->name('hero-slider-config');
    Route::put('hero-slider-config', [HomeContentController::class, 'updateHeroSliderConfig'])->name('hero-slider-config.update');

    // Database Maintenance
    Route::prefix('database')->name('database.')->group(function () {
        Route::get('/', [DatabaseController::class, 'index'])->name('index');
        Route::post('backup', [DatabaseController::class, 'backup'])->name('backup');
        Route::post('restore', [DatabaseController::class, 'restore'])->name('restore');
        Route::post('optimize', [DatabaseController::class, 'optimize'])->name('optimize');
        Route::post('clear-cache', [DatabaseController::class, 'clearCache'])->name('clear-cache');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('general', [ReportController::class, 'generalStats'])->name('general');
        Route::get('biodiversity', [ReportController::class, 'biodiversityIndex'])->name('biodiversity');
        Route::get('biodiversity/data', [ReportController::class, 'biodiversityData'])->name('biodiversity.data');
        Route::get('biodiversity/pdf', [ReportController::class, 'biodiversityReport'])->name('biodiversity.pdf');
        Route::get('publications', [ReportController::class, 'publicationsIndex'])->name('publications');
        Route::get('publications/data', [ReportController::class, 'publicationsData'])->name('publications.data');
        Route::get('publications/pdf', [ReportController::class, 'publicationsReport'])->name('publications.pdf');
        Route::get('biodiversity/excel', [ReportController::class, 'exportBiodiversityExcel'])->name('biodiversity.excel');
        Route::get('publications/excel', [ReportController::class, 'exportPublicationsExcel'])->name('publications.excel');
    });

    // System Configuration
    Route::prefix('configuration')->name('configuration.')->group(function () {
        Route::get('general', [ConfigurationController::class, 'general'])->name('general');
        Route::post('general', [ConfigurationController::class, 'updateGeneral'])->name('general.update');
        Route::get('users', [ConfigurationController::class, 'users'])->name('users.index');
        Route::get('users/create', [ConfigurationController::class, 'createUser'])->name('users.create');
        Route::post('users', [ConfigurationController::class, 'storeUser'])->name('users.store');
        Route::get('users/{user}/edit', [ConfigurationController::class, 'editUser'])->name('users.edit');
        Route::put('users/{user}', [ConfigurationController::class, 'updateUser'])->name('users.update');
        Route::delete('users/{user}', [ConfigurationController::class, 'destroyUser'])->name('users.destroy');
        Route::get('roles', [ConfigurationController::class, 'roles'])->name('roles');
        Route::post('roles', [ConfigurationController::class, 'storeRole'])->name('roles.store');
        Route::put('roles/{role}', [ConfigurationController::class, 'updateRole'])->name('roles.update');
        Route::delete('roles/{role}', [ConfigurationController::class, 'deleteRole'])->name('roles.delete');
    });
    
    // Site Settings (Menus and Logo)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('site', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'index'])->name('site');
        Route::post('logo', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'updateLogo'])->name('logo.update');
        Route::post('menus', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'updateMenus'])->name('menus.update');
        Route::post('initialize', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'initializeDefaultSettings'])->name('initialize');
    });

    // User Profile and Support
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('password', [ProfileController::class, 'showChangePassword'])->name('password');
        Route::put('password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('manual', [ProfileController::class, 'showManual'])->name('manual');
        Route::get('support', [ProfileController::class, 'showSupport'])->name('support');
    });

    Route::prefix('support')->name('support.')->group(function () {
        Route::get('contact', [ProfileController::class, 'showSupport'])->name('contact');
        Route::post('contact', [ProfileController::class, 'sendSupportMessage'])->name('contact.send');
        Route::get('about', [ProfileController::class, 'about'])->name('about');
    });
});

// Authentication Routes
require __DIR__.'/auth.php';

// Nota: Se elimina Auth::routes() para evitar duplicación de rutas con routes/auth.php
// y se evita la ruta /home duplicada. La ruta principal ya está definida arriba.
