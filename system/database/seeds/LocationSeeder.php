<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $locations = [
            ['code' => '9901', 'name' => 'BG HASKOVO'],
            ['code' => '9915', 'name' => 'BG PLOVDİV'],
            ['code' => '9904', 'name' => 'BG SOFYA'],
            ['code' => '1600', 'name' => 'BURSA'],
            ['code' => '4110', 'name' => 'GEBZE'],
            ['code' => '9902', 'name' => 'BG KIRCAALI'],
            ['code' => '3400', 'name' => 'İSTANBUL ESENLER'],
            ['code' => '2201', 'name' => 'EDİRNE ASLANLI'],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(['code' => $location['code']], $location);
        }
    }
}
