<?php

namespace Database\Seeders;

use App\Models\Street;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StreetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $streets = json_decode(File::get(database_path('jsons/data/streets.json')), true);
        foreach($streets as $street) {
            Street::create([
                'uuid' => $street['uuid'],
                'id' => $street['id'],
                'name' => strtolower($street['name']),
            ]);
        }
    }
}
