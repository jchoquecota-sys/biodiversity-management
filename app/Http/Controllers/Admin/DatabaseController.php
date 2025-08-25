<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DatabaseController extends Controller
{
    /**
     * Muestra la vista principal de mantenimiento de base de datos
     */
    public function index()
    {
        $backups = Storage::files('backups');
        $lastBackup = !empty($backups) ? Storage::lastModified(end($backups)) : null;
        
        // Obtener lista de tablas de la base de datos
        $tables = DB::select('SHOW TABLES');
        $tableNames = [];
        foreach ($tables as $table) {
            $tableArray = (array) $table;
            $tableNames[] = array_values($tableArray)[0];
        }
        
        return view('admin.database.index', compact('backups', 'lastBackup', 'tableNames'));
    }

    /**
     * Realiza un respaldo de la base de datos
     */
    public function backup()
    {
        try {
            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            
            // Asegurarse de que el directorio de respaldos exista
            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }
            
            // Ejecutar el comando de respaldo usando mysqldump
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                storage_path('app/backups/' . $filename)
            );
            
            exec($command);
            
            return redirect()->route('admin.database.index')
                ->with('success', 'Respaldo creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.database.index')
                ->with('error', 'Error al crear el respaldo: ' . $e->getMessage());
        }
    }

    /**
     * Restaura la base de datos desde un respaldo
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt'
        ]);

        try {
            $file = $request->file('backup_file');
            $filename = 'restore-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            
            // Guardar el archivo subido temporalmente
            $path = $file->storeAs('backups', $filename);
            $fullPath = storage_path('app/' . $path);
            
            // Ejecutar el comando de restauración
            $command = sprintf(
                'mysql -u%s -p%s %s < %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $fullPath
            );
            
            exec($command);
            
            return redirect()->route('admin.database.index')
                ->with('success', 'Base de datos restaurada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.database.index')
                ->with('error', 'Error al restaurar la base de datos: ' . $e->getMessage());
        }
    }

    /**
     * Optimiza las tablas de la base de datos
     */
    public function optimize(Request $request)
    {
        $request->validate([
            'tables' => 'required|array|min:1',
            'tables.*' => 'string'
        ]);

        try {
            $tables = $request->input('tables');
            $tableList = implode(',', $tables);
            
            DB::statement('OPTIMIZE TABLE ' . $tableList);
            
            return redirect()->route('admin.database.index')
                ->with('success', 'Tablas optimizadas exitosamente: ' . implode(', ', $tables));
        } catch (\Exception $e) {
            return redirect()->route('admin.database.index')
                ->with('error', 'Error al optimizar las tablas: ' . $e->getMessage());
        }
    }

    /**
     * Limpia la caché del sistema
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return redirect()->route('admin.database.index')
                ->with('success', 'Caché limpiada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.database.index')
                ->with('error', 'Error al limpiar la caché: ' . $e->getMessage());
        }
    }
}