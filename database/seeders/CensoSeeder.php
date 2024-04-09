<?php

namespace Database\Seeders;

use App\Models\Dwelling;
use App\Models\Neighbor;
use App\Models\Street;
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
            $lastname = $item['APELLIDOS'];

            $neighbor = Neighbor::firstOrNew([
                'phone_number' => $phone_number
            ]);

            // validar si el neighbor ya está registrado
            if ($neighbor->exists) {
                echo "El vecino con el número de teléfono {$phone_number} ya está registrado\n";
                continue;
            }

            $street = Street::where('name', 'like', "%{$item['CALLE']}%")->first();

            if (!$street) {
                echo "La calle {$item['CALLE']} no existe\n";
                continue;
            }

            $dwelling = Dwelling::where('street_uuid', $street->uuid)->where('street_number', $item['EXTERIOR'])->where('interior_number', $item['INTERIOR'])->first();

            if (!$dwelling) {
                echo "La vivienda con la calle {$item['CALLE']}, número exterior {$item['EXTERIOR']} e interior {$item['INTERIOR']} no existe\n";
                continue;
            }

            echo "Registrando al vecino con el número de teléfono {$phone_number}\n";

            $neighbor->firstname = $firstname;
            $neighbor->lastname = $lastname;

            $neighbor->save();

            $dwelling->neighbors()->attach($neighbor);
        }

        $censo_telefonos = json_decode(File::get(database_path('jsons/censo_telefonos.json')), true);

        foreach($censo_telefonos as $item) {
            $phone_number = $item['TELEFONO'];
            $firstname = $item['NOMBRE'];
            $lastname = $item['APELLIDO'];

            $neighbor = Neighbor::firstOrNew([
                'phone_number' => $phone_number
            ]);

            if ($neighbor) {
                echo "El vecino con el número de teléfono {$phone_number} ya existe\n";
                continue;
            }

            $street = Street::where('name', 'like', "%{$item['CALLE']}%")->first();

            if (!$street) {
                echo "La calle {$item['CALLE']} no existe\n";
                continue;
            }

            $dwelling = Dwelling::where('street_uuid', $street->uuid)->where('street_number', $item['EXTERIOR'])->where('interior_number', $item['INTERIOR'])->first();

            if (!$dwelling) {
                echo "La vivienda con la calle {$item['CALLE']}, número exterior {$item['EXTERIOR']} e interior {$item['INTERIOR']} no existe\n";
                continue;
            }

            echo "Registrando al vecino con el número de teléfono {$phone_number}\n";
            $neighbor->firstname = $firstname;
            $neighbor->lastname = $lastname;
            $neighbor->save();

            $dwelling->neighbors()->attach($neighbor);
        }

    }
}
