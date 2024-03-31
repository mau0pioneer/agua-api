<?php

namespace Database\Seeders;

use App\Models\Dwelling;
use App\Models\Street;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class DwellingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener el archivo JSON y convertirlo en un array
        $properties = json_decode(File::get(database_path('jsons/data/Propiedades.json')), true);
        // $codes = json_decode(File::get(database_path('jsons/codes.json')), true);

        // Recorrer el array y obtener los datos
        foreach ($properties as $property_data) {
            // generar codigo de 8 digitos incluyendo numeros y letras minusculas y mayusculas y los siguientes caracteres especiales: !@#$%*

            $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%*'), 0, 8);
            Dwelling::create([
                'street_number' => $property_data['street_number'] ? $property_data['street_number'] : null,
                'interior_number' => $property_data['interior_number'] ? strtolower(trim($property_data['interior_number'])) : null,
                'street_uuid' => $property_data['street_uuid'],
                'type' => $property_data['type'],
                'inhabited' => $property_data['inhabited'],
                'coordinates_uuid' => $property_data['coordinates_uuid'],
                'id' => $property_data['id'],
                'uuid' => $property_data['uuid'],
                'access_code' => $property_data['access_code'],
            ]);
        }

        /* 
foreach ($codes as $code_data) {
            if ($code_data['access_code']) {
                $dwelling = Dwelling::where('uuid', $code_data['uuid'])->first();
                $dwelling->access_code = $code_data['access_code'];
                $dwelling->save();
            }
        }        
        
        */

        $dwellings = json_decode(File::get(database_path('jsons/dwellings_update.json')), true);

        foreach ($dwellings as $dwelling_data) {
            $dwelling = Dwelling::where('uuid', $dwelling_data['uuid'])->first();
            $dwelling->street_number = $dwelling_data['street_number'];
            $dwelling->save();
        }

    }
}
