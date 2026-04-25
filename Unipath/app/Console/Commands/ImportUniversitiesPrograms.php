<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UniversitiesProgramsImport;

class ImportUniversitiesPrograms extends Command
{
    protected $signature = 'app:import-universities-programs';
    protected $description = 'Import universities and programs from the Excel file';

    public function handle(): int
    {
        $path = storage_path('app/imports/fyp_database_columns.xlsx');

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }

        try {
            DB::transaction(function () use ($path) {
                Excel::import(new UniversitiesProgramsImport, $path);
            });

            $this->info('Import completed successfully.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}