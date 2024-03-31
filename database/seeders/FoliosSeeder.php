<?php

namespace Database\Seeders;

use App\Models\Contribution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FoliosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // crear 440 folios de la tabla contributions
        for ($i = 1; $i <= 1000; $i++) {
            Contribution::create([
                // folio example: 2024-TA-0001
                'folio' => '2024-TA-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'amount' => 0,
            ]);
        }

        $folios = json_decode(File::get(database_path('jsons/folios.json')), true);
        
        foreach ($folios as $data) {
            $folio_name = '2024-TA-' . str_pad($data['folio'], 5, '0', STR_PAD_LEFT);
            $folio = Contribution::where('folio', $folio_name)->first();

            if(!$folio) continue;

            $folio->uuid = $data['uuid'];
            $folio->save();
        }
    }
}
