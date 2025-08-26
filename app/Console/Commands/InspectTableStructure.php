<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InspectTableStructure extends Command
{
    protected $signature = 'inspect:table-structure {table}';
    protected $description = 'Inspect the structure of a database table';

    public function handle()
    {
        $tableName = $this->argument('table');
        
        if (!Schema::hasTable($tableName)) {
            $this->error("Table '{$tableName}' does not exist.");
            return 1;
        }
        
        $this->info("=== STRUCTURE OF TABLE: {$tableName} ===");
        
        // Get column information using raw SQL
        $columns = DB::select("DESCRIBE {$tableName}");
        
        foreach ($columns as $column) {
            $this->line(sprintf(
                "%-20s | %-15s | %-5s | %-5s | %-10s | %s",
                $column->Field,
                $column->Type,
                $column->Null,
                $column->Key,
                $column->Default ?? 'NULL',
                $column->Extra
            ));
        }
        
        return 0;
    }
}