<?php

namespace Database\Seeders;

use App\Models\Neighbor;
use App\Models\Signature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class NeighborsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener el archivo JSON y convertirlo en un array
        $owners = json_decode(File::get(database_path('jsons/data/owners.json')), true);
        $ownersProperties = json_decode(File::get(database_path('jsons/data/owners_properties.json')), true);

        // Recorrer el array y obtener los datos
        foreach ($owners as $owner) {
            $neighbor = Neighbor::firstOrNew([
                'uuid' => $owner['uuid'],
                'firstname' => strtolower(trim($owner['firstname'])),
                'lastname' => strtolower(trim($owner['lastname'])),
            ]);

            $neighbor->phone_number = $owner['phone_number'] ? trim($owner['phone_number']) : null;
            $neighbor->prefix = strtolower(trim($owner['prefix']));
            $neighbor->alias = strtolower(trim($owner['alias']));
            $neighbor->attitude = $owner['attitude'];
            $neighbor->comments = $owner['comments'];
            $neighbor->save();

            // Recorrer el array de propiedades y obtener los datos
            foreach ($ownersProperties as $ownerProperty) {
                if ($ownerProperty['owner_uuid'] === $neighbor->uuid) {
                    $signature = Signature::firstOrNew([
                        'neighbor_uuid' => $neighbor->uuid,
                        'dwelling_uuid' => $ownerProperty['property_uuid'],
                    ]);
                    $signature->save();
                }
            }
        }
    }
}
