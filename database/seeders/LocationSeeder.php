<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. MAIN LOBBY
        $lobby = Location::firstOrCreate(['name' => 'Main Lobby']);
        Location::firstOrCreate(['name' => 'Check-out Lounge', 'parent_id' => $lobby->id]);
        Location::firstOrCreate(['name' => 'Arrival Lobby', 'parent_id' => $lobby->id]);
        Location::firstOrCreate(['name' => 'Late Check-out Lobby', 'parent_id' => $lobby->id]);
        Location::firstOrCreate(['name' => 'Lobby Parking', 'parent_id' => $lobby->id]);
        Location::firstOrCreate(['name' => 'Reception', 'parent_id' => $lobby->id]);

        // 2. BACK OF HOUSE
        $boh = Location::firstOrCreate(['name' => 'Back Of House']);
        
        // BOH Offices
        $bohOffice = Location::firstOrCreate(['name' => 'BOH Office', 'parent_id' => $boh->id]);
        $offices = [
            'Sales Office', 'Engineering Office', 'Finance Office', 'GM Office', 
            'Director of Room Office', 'Reservation Office', 'Food & Beverage Office', 
            'Security Office', 'Housekeeping Office', 'IT Office', 'SPA office', 
            'Purchasing Office', 'FO Office'
        ];
        foreach ($offices as $office) {
            Location::firstOrCreate(['name' => $office, 'parent_id' => $bohOffice->id]);
        }

        // Lockers
        $locker = Location::firstOrCreate(['name' => 'Locker', 'parent_id' => $boh->id]);
        Location::firstOrCreate(['name' => 'Male Changing Room', 'parent_id' => $locker->id]);
        Location::firstOrCreate(['name' => 'Female Changing Room', 'parent_id' => $locker->id]);
        Location::firstOrCreate(['name' => 'Outsourcing Locker', 'parent_id' => $locker->id]);

        // Collage Area
        $collage = Location::firstOrCreate(['name' => 'Collage Area', 'parent_id' => $boh->id]);
        Location::firstOrCreate(['name' => 'Collage Area - Service', 'parent_id' => $collage->id]);
        Location::firstOrCreate(['name' => 'Collage Area - Dishwashing', 'parent_id' => $collage->id]);
        Location::firstOrCreate(['name' => 'Collage Area - Preparation', 'parent_id' => $collage->id]);
        Location::firstOrCreate(['name' => 'Collage Area - Kitchen', 'parent_id' => $collage->id]);

        // Other BOH
        Location::firstOrCreate(['name' => 'Uniform Room', 'parent_id' => $boh->id]);
        Location::firstOrCreate(['name' => 'Banquet', 'parent_id' => $boh->id]);
        Location::firstOrCreate(['name' => 'Ballroom', 'parent_id' => $boh->id]);
        Location::firstOrCreate(['name' => 'Ballroom Corridor', 'parent_id' => $boh->id]);
        Location::firstOrCreate(['name' => 'Laundry', 'parent_id' => $boh->id]);
        Location::firstOrCreate(['name' => 'Loading Dock', 'parent_id' => $boh->id]);

        // Main Kitchen
        $kitchen = Location::firstOrCreate(['name' => 'Main Kitchen', 'parent_id' => $boh->id]);
        $kitchenAreas = [
            'Sanitising Room', 'Trolley Wash', 'Receiving Area', 'General Chiller/Freezer',
            'Beverage Storage', 'Meat Preparation', 'Fish Preparation', 'Vegetable Preparation',
            'BBQ Room', 'Stewarding Area', 'Hot Kitchen', 'Pastry & Bakery Area', 'Potwash',
            'Diary Storage', 'Cold Kitchen'
        ];
        foreach ($kitchenAreas as $area) {
            Location::firstOrCreate(['name' => $area, 'parent_id' => $kitchen->id]);
        }

        Location::firstOrCreate(['name' => 'Genset Room', 'parent_id' => $boh->id]);
        Location::firstOrCreate(['name' => 'Panel Room', 'parent_id' => $boh->id]);

        // 3. SPA
        $spa = Location::firstOrCreate(['name' => 'SPA']);
        $spaAreas = [
            'Hammam', 'Vichy', 'Dry Relaxation', 'Wet Relaxation', 'Treatment Room',
            'Changing Room - Male', 'Changing Room - Female', 'Gym Area', 'Salon Area',
            'Boutique Retail', 'Reception Area', 'Corridors Area'
        ];
        foreach ($spaAreas as $area) {
            Location::firstOrCreate(['name' => $area, 'parent_id' => $spa->id]);
        }
    }
}
