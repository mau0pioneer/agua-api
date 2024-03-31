<?php

namespace Database\Seeders;

use App\Models\Neighbor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CensoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $censo = json_decode(File::get(database_path('jsons/censo.json')), true);

        foreach($censo as $item) {
            $phone_number = $item['TELEFONO'];
            $firstname = $item['NOMBRE'];
            $lastname = $item['APELLIDO'];

            $neighbor = Neighbor::firstOrNew([
                'phone_number' => $phone_number
            ]);

            // validar si el neighbor ya está registrado
            if ($neighbor->exists) {
                echo "El vecino con el número de teléfono {$phone_number} ya está registrado\n";
                continue;
            }

            echo "Registrando al vecino con el número de teléfono {$phone_number}\n";

            $neighbor->firstname = $firstname;
            $neighbor->lastname = $lastname;

            $neighbor->save();
        }
    }
}
