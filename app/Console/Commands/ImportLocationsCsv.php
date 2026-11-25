<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class ImportLocationsCsv extends Command
{
    protected $signature = 'import:locations-csv';
    protected $description = 'Import hierarchical locations from the provided CSV file';

    public function handle()
    {
        $filePath = base_path('11. MASTER DATA - LOCATION.xlsx - Sheet1.csv');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return;
        }

        $file = fopen($filePath, 'r');
        
        // Skip first 5 lines (headers and fluff)
        for ($i = 0; $i < 5; $i++) {
            fgetcsv($file);
        }

        $lastRoot = null;
        $lastSub = null;
        $count = 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($file)) !== FALSE) {
                // Skip empty rows
                if (empty(array_filter($row))) continue;

                $rootName = trim($row[1] ?? '');
                $subName = trim($row[3] ?? '');
                $subSubName = trim($row[4] ?? '');

                // Handle Root Location
                if (!empty($rootName)) {
                    $lastRoot = Location::updateOrCreate(
                        ['name' => $rootName, 'parent_id' => null],
                        ['type' => 'Building']
                    );
                    $lastSub = null;
                    $count++;
                }

                // Handle Sub Location
                if (!empty($subName)) {
                    if (!$lastRoot) {
                        $this->warn("Skipping sub-location '$subName' because no root location was found.");
                        continue;
                    }
                    $lastSub = Location::updateOrCreate(
                        ['name' => $subName, 'parent_id' => $lastRoot->id],
                        ['type' => 'Floor/Area']
                    );
                    $count++;
                }

                // Handle Sub-Sub Location
                if (!empty($subSubName)) {
                    if (!$lastSub) {
                        $this->warn("Skipping sub-sub-location '$subSubName' because no sub-location was found.");
                        continue;
                    }
                    Location::updateOrCreate(
                        ['name' => $subSubName, 'parent_id' => $lastSub->id],
                        ['type' => 'Room']
                    );
                    $count++;
                }
            }
            DB::commit();
            $this->info("Import completed successfully. Processed $count locations.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error during import: " . $e->getMessage());
        }

        fclose($file);
    }
}
