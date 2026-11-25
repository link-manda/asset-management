<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitOfMeasurement;

class UnitOfMeasurementSeeder extends Seeder
{
    public function run(): void
    {
        $uoms = [
            ['name' => 'Unit', 'symbol' => 'Unit'],
            ['name' => 'Set', 'symbol' => 'Set'],
            ['name' => 'Pack', 'symbol' => 'Pack'],
            ['name' => 'Box', 'symbol' => 'Box'],
            ['name' => 'License', 'symbol' => 'Lic'],
        ];

        foreach ($uoms as $uom) {
            UnitOfMeasurement::updateOrCreate(['symbol' => $uom['symbol']], $uom);
        }
    }
}
