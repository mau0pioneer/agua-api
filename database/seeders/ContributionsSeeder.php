<?php

namespace Database\Seeders;

use App\Models\Collector;
use App\Models\Contribution;
use App\Models\Dwelling;
use App\Models\DwellingNeighbor;
use App\Models\Neighbor;
use App\Models\Period;
use App\Models\Street;
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
        $contributions = json_decode(File::get(database_path('jsons/contributions.json')), true);

        $months = [
            'ENERO' => '01',
            'FEBRERO' => '02',
            'MARZO' => '03',
            'ABRIL' => '04',
            'MAYO' => '05',
            'JUNIO' => '06',
            'JULIO' => '07',
            'AGOSTO' => '08',
            'SEPTIEMBRE' => '09',
            'OCTUBRE' => '10',
            'NOVIEMBRE' => '11',
            'DICIEMBRE' => '12',
        ];

        foreach ($contributions as $data) {

            $folio = '2024-TA-' . str_pad($data['FOLIO'], 5, '0', STR_PAD_LEFT);
            $contribution = Contribution::where('folio', $folio)->first();

            if (!$contribution) {
                echo "Folio not found: $folio\n";
                continue;
            }

            if ($data['CANCELADO']) {
                echo "Canceled: $folio\n";
                $contribution->status = 'canceled';
                $contribution->save();
                continue;
            }

            $street = Street::where('name', 'like' , '%'.strtolower(trim($data['CALLE'])).'%')->first();

            if (!$street && $data['CALLE'] !== 'SANTO TOMAS') {
                echo "{$data['FOLIO']}. Street not found: {$data['CALLE']}\n";
                continue;
            }

            if ($data['CALLE'] === "SANTO TOMAS") {
                $street_san_marcos = Street::where('name', 'san marcos')->first();

                $dwelling = Dwelling::where('street_uuid', $street_san_marcos->uuid)->where('street_number', $data['EXTERIOR'])->where('interior_number', $data['INTERIOR'])->first();

                if (!$dwelling) {
                    $dwelling = Dwelling::where('street_uuid', $street->uuid)->where('street_number', $data['EXTERIOR'])->where('interior_number', $data['INTERIOR'])->first();

                    if (!$dwelling) {
                        echo "{$data['FOLIO']}. Dwelling not found: {$data['CALLE']} {$data['EXTERIOR']} {$data['INTERIOR']}\n";
                        continue;
                    }
                }

                $dwelling->street_uuid = $street->uuid;
                $dwelling->save();
                echo "Folio: {$data['FOLIO']}. Cambiando calle de san marcos a santo tomas\n";
            } else {
                $dwelling = Dwelling::where('street_uuid', $street->uuid)->where('street_number', $data['EXTERIOR'])->where('interior_number', $data['INTERIOR'])->first();
            }

            // si no existe la vivienda, se crea
            if (!$dwelling) {
                echo "{$data['FOLIO']}. Dwelling not found: {$data['CALLE']} {$data['EXTERIOR']} {$data['INTERIOR']}\n";
                /*
                $dwelling = Dwelling::create([
                    'street_uuid' => $street->uuid,
                    'street_number' => $data['EXTERIOR'],
                    'interior_number' => $data['INTERIOR'],
                    'type' => 1,
                    'inhabited' => 1,
                ]);
                */
                continue;
            }

            if ($data['TELEFONO']) {
                $neighbor = Neighbor::firstOrNew([
                    'phone_number' => strval($data['TELEFONO']),
                ]);
                $neighbor->firstname = strtolower($data['NOMBRE']);
                $neighbor->lastname = strtolower($data['APELLIDOS']);
            } else {
                $neighbor = Neighbor::firstOrNew([
                    'firstname' => strtolower($data['NOMBRE']),
                    'lastname' => strtolower($data['APELLIDOS']),
                ]);
            }

            $neighbor->save();

            $dwellingNeighbor = DwellingNeighbor::firstOrNew([
                'dwelling_uuid' => $dwelling->uuid,
                'neighbor_uuid' => $neighbor->uuid,
            ]);
            $dwellingNeighbor->condition = $data['CONDICION'] ? 'owner' : 'renter';
            $dwellingNeighbor->save();

            // colector de la contribución where like
            $collector_name = trim(strtolower($data['COLECTOR']));

            if($collector_name === 'china') $collector_name = 'griselda';
            if($collector_name === 'pizzeria') $collector_name = 'edmundo';
            if($collector_name === 'paty') $collector_name = 'patricia';
            if($collector_name === 'hilario') $collector_name = 'hilario';
            if($collector_name === 'socorro') $collector_name = 'maria del socorro';
            
            $collector = Collector::where('name', 'like', '%' . $collector_name . '%')->first();

            if (!$collector) {
                echo "FOLIO: {$data['FOLIO']}. Collector not found: {$data['COLECTOR']}\n";
                continue;
            }

            $periods = [];
            foreach (explode(',', $data['PERIODOS']) as $data_period) {
                if (!$data_period) continue;

                $year_and_month = explode('-', $data_period);
                if(count($year_and_month) < 2) {
                    echo "FOLIO: {$data['FOLIO']}. Month and Year not found: {$year_and_month[0]}\n";
                    continue;
                }
                $year = $year_and_month[1];

                // validar si existe el mes en el array de meses
                if (!array_key_exists($year_and_month[0], $months)) {
                    echo "FOLIO: {$data['FOLIO']}. Month not found: {$year_and_month[0]}\n";
                    continue;
                }

                $month = $months[$year_and_month[0]];

                $period = Period::firstOrNew([
                    'year' => $year,
                    'month' => $month,
                    'dwelling_uuid' => $dwelling->uuid,
                ]);

                $period->amount = 0;
                $period->status = 'paid';
                $period->save();
                $periods[] = $period;
            }

            $contribution->status = 'finalized';
            $contribution->amount = $data['CANTIDAD'];
            $contribution->comments = $data['TIPO'] === 'PERIODO' ? (
                'Pago de ' . (count($periods) === 1 ? 'periodo' : 'periodos') . ': ' . implode(', ', array_map(function ($period) {
                    $months = [
                        'ENERO' => '01',
                        'FEBRERO' => '02',
                        'MARZO' => '03',
                        'ABRIL' => '04',
                        'MAYO' => '05',
                        'JUNIO' => '06',
                        'JULIO' => '07',
                        'AGOSTO' => '08',
                        'SEPTIEMBRE' => '09',
                        'OCTUBRE' => '10',
                        'NOVIEMBRE' => '11',
                        'DICIEMBRE' => '12',
                    ];
                    $monthsInverted = array_flip($months);

                    $monthLower = strtolower($monthsInverted[$period->month]);
                    $month = ucfirst($monthLower);

                    return $month . '-' . $period->year;
                }, $periods))
            ) : 'Reconexión';
            $contribution->comments = $contribution->comments . '. ' . $data['DESCRIPCION'];
            $contribution->collector_uuid = $collector->uuid;
            $contribution->neighbor_uuid = $neighbor->uuid;
            $contribution->dwelling_uuid = $dwelling->uuid;

            // convertir fecha con el siguiente formato: 1708473600000 a formato mysql 2023-12-31
            //$date = explode('-', date('d-m-Y', $data['FECHA'] / 1000));
            //$contribution->created_at = $date[2] . '-' . $date[1] . '-' . $date[0];
            $contribution->created_at = $data['FECHA'];
            $contribution->save();
        }
    }
}
