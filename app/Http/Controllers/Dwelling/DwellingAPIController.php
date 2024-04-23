<?php

namespace App\Http\Controllers\Dwelling;

use App\Helpers\APIHelper;
use App\Http\Controllers\APIController;
use App\Models\AccessDwelling;
use App\Models\Contribution;
use App\Models\Dwelling;
use App\Models\Neighbor;
use App\Models\Period;
use App\Repositories\DwellingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DwellingAPIController extends APIController
{
    public function __construct(DwellingRepository $dwellingRepository)
    {
        $this->repository = $dwellingRepository;
    }

    public function changeInhabited(Request $request, $uuid)
    {
        try {
            $dwelling = $this->repository->changeInhabited($uuid, $request->get('inhabited'));
            return response()->json($dwelling->inhabited, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to update data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getContributions($uuid)
    {
        try {
            $contributions = $this->repository->getContributions($uuid);
            return response()->json($contributions, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getNeighbors($uuid)
    {
        try {
            $neighbors = $this->repository->getNeighbors($uuid);
            return response()->json($neighbors, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getPeriods($uuid)
    {
        try {
            $periods = $this->repository->getPeriods($uuid);
            return response()->json($periods, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getPendingPeriods($uuid)
    {
        try {
            $periods = $this->repository->getPendingPeriods($uuid);
            return response()->json($periods, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function storePeriod(Request $request, $uuid)
    {
        try {
            $errors = $this->validateRules($request, null, Period::$rules, 'periods');
            if ($errors) return $errors;

            $dwelling = Dwelling::where('uuid', $uuid)->first();

            if (!$dwelling) {
                return response()->json([
                    "message" => "No se encontró la vivienda.",
                    "errors" => [
                        'dwelling_uuid' => ['La vivienda no existe.']
                    ]
                ], 404);
            }

            // validar si el periodo ya existe
            $period = Period::where('year', $request->get('year'))
                ->where('month', $request->get('month'))
                ->where('dwelling_uuid', $uuid)
                ->first();

            if ($period) {
                return response()->json([
                    'message' => $period->status === 'finalized' ? 'El periodo ya fue finalizado.' : 'El periodo ya existe.',
                    'errors' => [
                        'base' => ['El periodo ya existe.']
                    ]
                ], 400);
            }

            $period = Period::create([
                'year' => $request->get('year'),
                'month' => $request->get('month'),
                'amount' => 100,
                'dwelling_uuid' => $uuid,
                'status' => 'pending',
            ]);

            return response()->json($period, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to store data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getNeigborsBySignatures($uuid)
    {
        try {
            $dwelling = Dwelling::where('uuid', $uuid)->first(['uuid']);
            $neighbors = $dwelling->neighbors()->get(['neighbors.uuid', 'firstname', 'lastname']);
            return response()->json($neighbors, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getNeighborsFromContributions($uuid)
    {
        try {
            $dwelling = Dwelling::where('uuid', $uuid)->first();
            // obtener los vecinos que han hecho contribuciones a la vivienda pero que no estén duplicados
            $neighbors = $dwelling->neighbors()->whereHas('contributions', function ($query) use ($uuid) {
                $query->where('dwelling_uuid', $uuid);
            })->get(['neighbors.uuid', 'firstname', 'lastname', 'phone_number'])->unique('uuid');
            return response()->json($neighbors, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $uuid)
    {
        try {
            $dwelling = $this->repository->find($uuid);
            $dwelling->update($request->all());

            return response()->json($dwelling, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to update data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function storeContribution(Request $request, $uuid)
    {
        $months = [
            'enero' => '01',
            'febrero' => '02',
            'marzo' => '03',
            'abril' => '04',
            'mayo' => '05',
            'junio' => '06',
            'julio' => '07',
            'agosto' => '08',
            'septiembre' => '09',
            'octubre' => '10',
            'noviembre' => '11',
            'diciembre' => '12',
        ];

        try {
            DB::beginTransaction();
            $dwelling = Dwelling::where('uuid', $uuid)->first();

            $neighbor_uuid = $request->get('neighbor_uuid');

            if ($neighbor_uuid) {
                $neighbor = Neighbor::where('uuid', $neighbor_uuid)->first();
            } else {
                $neighbor = Neighbor::create([
                    'firstname' => $request->get('firstname'),
                    'lastname' => $request->get('lastname'),
                    'phone_number' => $request->get('phone_number'),
                ]);
            }

            $periods = explode(',', $request->get('periods'));
            foreach ($periods as $period) {
                if ($period === '') continue;
                $array = explode('-', $period);
                $month = strtolower($array[0]);

                $period = Period::firstOrNew([
                    'month' => $months[$month],
                    'year' => $array[1],
                    'dwelling_uuid' => $dwelling->uuid,
                ]);

                $period->amount = 0;
                $period->status = 'paid';
                $period->save();
            }

            $contribution = Contribution::create([
                'amount' => $request->get('amount'),
                'folio' => $request->get('folio'),
                'comments' => $request->get('comments'),
                'neighbor_uuid' => $neighbor->uuid,
                'dwelling_uuid' => $dwelling->uuid,
                'status' => 'finalized',
            ]);

            DB::commit();

            $dwelling = Dwelling::where('uuid', $uuid)->with([
                'street' => function ($query) {
                    // obtener solo el nombre de la calle y uuid de la calle
                    $query->select(['uuid', 'name']);
                },
            ])->first(['id', 'uuid', 'street_uuid', 'street_number', 'interior_number', 'inhabited', 'type', 'comments']);
            $dwelling = [
                'id' => $dwelling->id,
                'uuid' => $dwelling->uuid,
                'street_uuid' => $dwelling->street_uuid,
                'street_number' => $dwelling->street_number,
                'interior_number' => $dwelling->interior_number,
                'inhabited' => $dwelling->inhabited,
                'type' => $dwelling->type,
                'comments' => $dwelling->comments,
                'street_name' => $dwelling->street->name,
                'pending_periods' => $dwelling->periods()->where('status', 'pending')->count(),
                'contributions_count' => $dwelling->contributions()->count(),
            ];

            return response()->json([
                'dwelling' => $dwelling,
                'contribution' => $contribution,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to store data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getDataByAccessCode(Request $request)
    {
        try {
            $accessCode = $request->get('access_code');

            if (!$accessCode) {
                return response()->json([
                    'message' => 'No se proporcionó el código de acceso.'
                ], 400);
            }

            $dwelling = Dwelling::where('access_code', $accessCode)->with([
                'street' => function ($query) {
                    $query->select(['uuid', 'name']);
                },
                'contributions' => function ($query) {
                    // obtener solo el uuid, monto, folio, comentarios, fecha de creación, uuid del vecino y uuid de la vivienda y los datos del vecino
                    $query->select(['uuid', 'amount', 'folio', 'comments', 'created_at', 'neighbor_uuid', 'dwelling_uuid', 'collector_uuid'])->with([
                        'neighbor' => function ($query) {
                            $query->select(['uuid', 'firstname', 'lastname', 'phone_number']);
                        }
                    ]);
                },
                'periods' => function ($query) {
                    // obtener solo el uuid, año, mes, estatus, monto y uuid de la vivienda y solo los que tengan el estatus pendiente
                    $query->select(['uuid', 'year', 'month', 'status', 'amount', 'dwelling_uuid'])->where('status', 'pending');
                },
            ])->first(['uuid', 'street_uuid', 'street_number', 'interior_number']);

            if (!$dwelling) {
                return response()->json([
                    'message' => 'No se encontró la vivienda con el código de acceso proporcionado.'
                ], 404);
            }

            AccessDwelling::create([
                'dwelling_uuid' => $dwelling->uuid
            ]);

            $contributions = $dwelling->contributions;

            foreach ($contributions as $contribution) {
                // obtener el colector que hizo la contribución
                $contribution->collector = $contribution->collector()->first(['uuid', 'name']);
            }

            return response()->json([
                'street_number' => $dwelling->street_number,
                'interior_number' => $dwelling->interior_number,
                'street_name' => $dwelling->street->name,
                'contributions' => $dwelling->contributions,
                'periods' => $dwelling->periods,
            ], 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getDwellings()
    {
        // obtener todas las viviendas que tengan al menos una neighbor
        $dwellings = Dwelling::whereHas('neighbors')->with([
            'neighbors',
            'street'
        ])->get();
        return response()->json($dwellings, 200);
    }

    public function findFromAddress(Request $request)
    {
        try {
            $street = $request->get('street_uuid');
            $streetNumber = $request->get('street_number');
            $interiorNumber = $request->get('interior_number');

            $dwelling = Dwelling::where('street_uuid', $street)
                ->where('street_number', $streetNumber)
                ->where('interior_number', $interiorNumber)
                ->first();

            if (!$dwelling) {
                return response()->json([
                    'message' => 'No se encontró la vivienda con la dirección proporcionada.'
                ], 404);
            }

            return response()->json([
                'id' => $dwelling->id,
                'uuid' => $dwelling->uuid,
                'street_uuid' => $dwelling->street_uuid,
                'street_number' => $dwelling->street_number,
                'interior_number' => $dwelling->interior_number,
                'inhabited' => $dwelling->inhabited,
                'type' => $dwelling->type,
                'type_color' => $dwelling->type_color,
                'comments' => $dwelling->comments,
                'street_name' => $dwelling->street->name,
                'pending_periods' => $dwelling->periods()->where('status', 'pending')->count(),
                'contributions_count' => $dwelling->contributions()->count(),
                'access_code' => $dwelling->access_code,
            ], 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function acuse()
    {
        // obtener las viviendas que sean de las calles san gabriel, san marcos y san esteban. 

        $dwellings = Dwelling::whereHas('street', function ($query) {
            $query->whereIn('name', ['san lazaro', 'san pascual', 'santa emiliana', 'san cosme']);
        })->get();

        return view('acuse', compact('dwellings'));
    }

    public function getLastContribution($uuid)
    {
        try {
            $period = $this->repository->getLastPeriod($uuid);

            if (is_null($period)) return response()->json([
                'message' => 'No se encontró la vivienda.'
            ], 404);

            return response()->json([
                'message' => strtolower("{$period->getMonth()} {$period->year}")
            ], 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function storeNeighbor(Request $request, $uuid)
    {
        try {
            $dwelling = $this->repository->find($uuid);

            // validar si existe
            if (is_null($dwelling->id)) {
                return response()->json([
                    'message' => 'No se encontró la vivienda.'
                ], 404);
            }

            if ($response = $this->validateRules($request, null, Neighbor::$rules, 'neighbors'))
                return $response;

            $neighbor = Neighbor::create($request->all());
            $dwelling->neighbors()->attach($neighbor->uuid);
            return response()->json($neighbor, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to store data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function profe()
    {
        // obtener las viviendas que tengan periodos pendientes y con un whereHas se obtienen solo las viviendas que tengan al menos una contribución hecha por un collector con uuid 1
        $dwellings = Dwelling::whereHas('periods', function ($query) {
            $query->where('status', 'pending');
        })->whereHas('contributions', function ($query) {
            $query->where('collector_uuid', '6c94521c-c898-4db2-ad78-20ee32ae6fd0');
        })->get();

        $dwellings = $dwellings->groupBy('street_uuid');

        return view('dwellings', ['dwellings' => $dwellings]);
    }

    public function profet()
    {
        // obtener los vecinos que no cuenten con numero de telefono y agruparlos por vivienda y calle
        $dwellings = Dwelling::whereHas('neighbors', function ($query) {
            $query->whereNull('phone_number');
        })->get();

        // agrupar las viviendas por calle junto con el nombre de la calle
        $streetsUuids = $dwellings->groupBy('street_uuid');
        return view('profet', ['streetsUuids' => $streetsUuids]);
    }

    public function getDwellings2()
    {
        // obtener las viviendas que tangan al menos una contribución
        $dwellings = Dwelling::whereHas('contributions')->get();
        $dwellingsWithDebt = [];
        $data = [];

        foreach ($dwellings as $dwelling) {
            // obtener el ultimo period de la vivienda con estatus pagado
            $lastPeriod = $dwelling->periods()->where('status', 'paid')->orderBy('year', 'desc')->orderBy('month', 'desc')->first();
            if (!$lastPeriod) {
                $dwellingsWithDebt[] = $dwelling;
                continue;
            }

            // comparar el mes y año del ultimo periodo pagado con el mes y año actual
            $now = date('Y-m-d');
            $date_period = $lastPeriod->year . '-' . $lastPeriod->month . '-01';


            // obtener en una variable los meses de diferencia entre la fecha actual y la fecha del ultimo periodo pagado
            $months = (int)date('m', strtotime($now)) - (int)date('m', strtotime($date_period));

            // pasar de negativo a positivo
            $months = intval(abs($months));

            if ($months > 2) {
                $dwellingsWithDebt[] = $dwelling;
                $firstname = $dwelling->neighbors()->first()->firstname || '';
                $lastname = $dwelling->neighbors()->first()->lastname || '';

                $data[] = [
                    'CALLE' => strtoupper($dwelling->street->name),
                    'NUMERO' => $dwelling->street_number,
                    'INTERIOR' => $dwelling->interior_number,
                    'NOMBRE' => strtoupper($firstname . ' ' . $lastname),
                    'TELEFONO' => $dwelling->neighbors()->first()->phone_number,
                    'ULTIMO_PAGO' => $lastPeriod->getMonth() . ' ' . $lastPeriod->year,
                ];
            }
        }

        return view('latest', ['dwellings' => $dwellingsWithDebt]);
    }
}
