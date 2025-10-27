<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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
            
            // Ejecutar el respaldo usando Symfony Process evitando exponer el password en la línea de comandos
            $username = (string) config('database.connections.mysql.username');
            $password = (string) config('database.connections.mysql.password');
            $database = (string) config('database.connections.mysql.database');
            $host = (string) (config('database.connections.mysql.host') ?? '127.0.0.1');
            $port = (string) (config('database.connections.mysql.port') ?? '3306');

            $process = new Process([
                'mysqldump',
                sprintf('--host=%s', $host),
                sprintf('--port=%s', $port),
                sprintf('--user=%s', $username),
                '--skip-comments',
                '--single-transaction',
                $database,
            ]);
            // Evitar que la contraseña sea visible en el proceso
            $process->setEnv(['MYSQL_PWD' => $password]);
            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Guardar el contenido del respaldo en el archivo de destino
            Storage::put('backups/' . $filename, $process->getOutput());
            
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
            
            // Restaurar usando Symfony Process y pasando el archivo como input
            $username = (string) config('database.connections.mysql.username');
            $password = (string) config('database.connections.mysql.password');
            $database = (string) config('database.connections.mysql.database');
            $host = (string) (config('database.connections.mysql.host') ?? '127.0.0.1');
            $port = (string) (config('database.connections.mysql.port') ?? '3306');

            $process = new Process([
                'mysql',
                sprintf('--host=%s', $host),
                sprintf('--port=%s', $port),
                sprintf('--user=%s', $username),
                $database,
            ]);
            $process->setEnv(['MYSQL_PWD' => $password]);
            $process->setTimeout(300);
            $process->setInput(file_get_contents($fullPath));
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            
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
            $requestedTables = $request->input('tables');

            // Validar los nombres de tablas contra las tablas existentes
            $allTables = array_map(function ($row) {
                $rowArray = (array) $row;
                return array_values($rowArray)[0] ?? null;
            }, DB::select('SHOW TABLES'));
            $validTables = array_values(array_intersect($allTables, $requestedTables));

            if (empty($validTables)) {
                return redirect()->route('admin.database.index')
                    ->with('error', 'No se seleccionaron tablas válidas para optimizar.');
            }

            // Citar correctamente los nombres de las tablas para evitar inyección
            $wrappedTables = array_map(function ($table) {
                return '`' . str_replace('`', '``', $table) . '`';
            }, $validTables);

            DB::statement('OPTIMIZE TABLE ' . implode(', ', $wrappedTables));

            return redirect()->route('admin.database.index')
                ->with('success', 'Tablas optimizadas exitosamente: ' . implode(', ', $validTables));
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