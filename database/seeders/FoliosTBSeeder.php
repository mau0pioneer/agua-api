<?php

namespace Database\Seeders;

use App\Models\Contribution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FoliosTBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $folios = json_decode(File::get(database_path('jsons/foliostb.json')), true);
        
        foreach ($folios as $data) {
            $folio = explode('.', $data['filename'])[0];
            $folio = explode('-', $folio)[2];
            $folio = '2024-TB-' . str_pad($folio, 5, '0', STR_PAD_LEFT);
            
            $uuid = $data['qr_values']['2200_100'];

            Contribution::create([
                'uuid' => $uuid,
                'folio' => $folio,
                'amount' => 0,
            ]);

            print($uuid . " Creado correctamente " . $folio . "\n");
        }
    }
}
