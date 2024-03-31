<?php

namespace Database\Seeders;

use App\Models\Collector;
use App\Models\Contribution;
use App\Models\Dwelling;
use App\Models\Neighbor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ContributionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payments = json_decode(File::get(database_path('jsons/data/Pagos.json')), true);

        foreach ($payments as $payment) {
            $neighbor = Neighbor::firstOrNew([
                'firstname' => strtolower(trim($payment['firstname'])),
                'lastname' => strtolower(trim($payment['lastname'])),
            ]);

            if (!is_null($payment['phone_number'])) {
                if (!Neighbor::where('phone_number', $payment['phone_number'])->exists()) {
                    echo "No existe el vecino con el número de teléfono: " . $payment['phone_number'] . "\n";
                    $neighbor->phone_number = $payment['phone_number'] ? trim($payment['phone_number']) : null;
                } else {
                    echo "Existe el vecino con el número de teléfono: " . $payment['phone_number'] . "\n";
                }
            }

            $neighbor->save();

            $dwelling = Dwelling::where('id', $payment['property_id'])->first();

            $eder = str_contains($payment['comments'], 'EDER') || str_contains($payment['comments'], 'eder');

            $china = str_contains($payment['comments'], 'CHINA') || str_contains($payment['comments'], 'china');

            $azucena = str_contains($payment['comments'], 'AZUCENA') || str_contains($payment['comments'], 'azucena');

            $pizzeria = str_contains($payment['comments'], 'PIZZERIA') || str_contains($payment['comments'], 'pizzeria');

            if ($eder) {
                $collector = Collector::where('name', 'Eder Perez Romero')->first();
            } elseif ($china) {
                $collector = Collector::where('name', 'China')->first();
            } elseif ($azucena) {
                $collector = Collector::where('name', 'Azucena')->first();
            } elseif ($pizzeria) {
                $collector = Collector::where('name', 'Pizzeria Piztak')->first();
            }

            $folio = null;
            // convertir payment.folio a integer
            if (isset($payment['folio'])) {
                $folio = (int) $payment['folio'];
                // validar que el folio sea mayor a 0 y sea un número válido
                if ($folio <= 0 || !is_int($folio)) {
                    $folio = null;
                }
            }

            Contribution::create([
                'neighbor_uuid' => $neighbor->uuid,
                'dwelling_uuid' => $dwelling->uuid,
                'collector_uuid' => $collector->uuid ?? null,
                'amount' => $payment['amount'],
                'status' => 'finalized',
                'folio' => $folio,
                'created_at' => $payment['created_at'],
            ]);
        }
    }
}
