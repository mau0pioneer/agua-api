<?php

namespace Database\Seeders;

use App\Models\Dwelling;
use App\Models\Neighbor;
use App\Models\Street;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class PhonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $excel_path = database_path('excel/phones.xlsx');
        $data = Excel::toArray([], $excel_path);

        foreach ($data[0] as $i => $row) {
            if ($i == 0) continue;
            $street_name = trim(strtolower($row[0]));
            $street_number = trim($row[1]);
            $interior_number = trim(strtolower($row[2]));
            $firstname = trim(strtolower($row[3]));
            $lastname = trim(strtolower($row[4]));
            $phone_number = trim($row[5]);

            if (empty($phone_number)) {
                print("Phone number is empty\n");
                continue;
            }

            $neighbor = Neighbor::firstOrNew([
                'phone_number' => $phone_number
            ]);

            if ($neighbor->exists) {
                print("Phone number $phone_number already exists\n");
                continue;
            }

            if (empty($street_name) || empty($street_number)) {
                print("Street name or number is empty\n");
                continue;
            }

            $street = Street::where('name', 'like', '%' . $street_name . '%')->first();
            if (!$street) {
                print("Street $street_name not found\n");
                continue;
            }

            if ($interior_number) {
                $dwelling = Dwelling::where('street_uuid', $street->uuid)
                    ->where('street_number', $street_number)
                    ->where('interior_number', $interior_number)
                    ->first();
            } else {
                $dwelling = Dwelling::where('street_uuid', $street->uuid)
                    ->where('street_number', $street_number)
                    ->whereNull('interior_number')
                    ->first();
            }

            if (!$dwelling) {
                print("Dwelling $street_name $street_number $interior_number not found\n");
                continue;
            }

            $neighbor->firstname = $firstname;
            $neighbor->lastname = $lastname;
            $neighbor->save();
            $dwelling->neighbors()->attach($neighbor->uuid);
            print("Phone number $phone_number added\n");
        }
    }
}
