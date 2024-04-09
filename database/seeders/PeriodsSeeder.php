<?php

namespace Database\Seeders;

use App\Models\Dwelling;
use App\Models\Period;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PeriodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dwellings = Dwelling::all();

        foreach ($dwellings as $dwelling) {
            $period = Period::firstOrNew([
                'month' => '01',
                'year' => 2024,
                'dwelling_uuid' => $dwelling->uuid,
            ]);
            if (!$period->id) {
                $period->amount = 100;
                $period->status = 'pending';
                $period->save();
            }

            $period = Period::firstOrNew([
                'month' => '02',
                'year' => 2024,
                'dwelling_uuid' => $dwelling->uuid,
            ]);
            if (!$period->id) {
                $period->amount = 100;
                $period->status = 'pending';
                $period->save();
            }

            $period = Period::firstOrNew([
                'month' => '03',
                'year' => 2024,
                'dwelling_uuid' => $dwelling->uuid,
            ]);
            if (!$period->id) {
                $period->amount = 100;
                $period->status = 'pending';
                $period->save();
            }

            $period = Period::firstOrNew([
                'month' => '04',
                'year' => 2024,
                'dwelling_uuid' => $dwelling->uuid,
            ]);

            if (!$period->id) {
                $period->amount = 100;
                $period->status = 'pending';
                $period->save();
            }
        }
    }
}
