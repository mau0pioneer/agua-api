<?php

namespace App\Http\Controllers\Dwelling;

use App\Helpers\APIHelper;
use App\Http\Controllers\APIController;
use App\Models\AccessDwelling;
use App\Models\Contribution;
use App\Models\Dwelling;
use App\Models\Neighbor;
use App\Models\Period;
use App\Models\Street;
use App\Repositories\DwellingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DwellingAPIController extends APIController
{
    public function __construct(DwellingRepository $dwellingRepository)
    {
        $this->repository = $dwellingRepository;
    }

    public function getDwellings2(){
        // obtener las viviendas que deban mas de 2 periodos pendientes y tengan al menos una contribución

        $dwellings = Dwelling::whereHas('periods', function ($query) {
            $query->select('dwelling_uuid')
                  ->where('status', 'pending')
                  ->groupBy('dwelling_uuid')
                  ->havingRaw('COUNT(id) > 2');
        })->whereHas('contributions')->get();
        

        return view('latest', compact('dwellings'));
    }

    public function getTitle($uuid)
    {
        try {
            $dwelling = Dwelling::with(['street' => function ($query) {
                // obtener solo el nombre de la calle y uuid de la calle
                $query->select(['uuid', 'name']);
            }])
                ->where('uuid', $uuid)->first(['street_uuid', 'street_number', 'interior_number']);

            return response()->json([
                'title' => $dwelling->street->name . ' ' . $dwelling->street_number . ' ' . $dwelling->interior_number
            ], 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function find($uuid)
    {
        try {
            // Obtiene un registro a partir del uuid y añade el atributo title compuesto por el nombre de la calle, número exterior e interior
            $dwelling = Dwelling::where('uuid', $uuid)->with([
                'street' => function ($query) {
                    // obtener solo el nombre de la calle y uuid de la calle
                    $query->select(['uuid', 'name']);
                },
            ])->first(['id', 'uuid', 'street_uuid', 'street_number', 'interior_number', 'inhabited', 'type', 'comments']);

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
            ], 200);
        } catch (\Exception $ex) {
            APIHelper::responseFailed([
                'message' => 'Failed to get data.',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }

    public function changeInhabited(Request $request, $uuid)
    {
        try {
            $dwelling = Dwelling::where('uuid', $uuid)->first(['id', 'inhabited', 'type']);
            $dwelling->inhabited = $request->inhabited !== null ?
                $request->inhabited : !$dwelling->inhabited;
            $dwelling->save();

            return response()->json($dwelling, 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
                'message' => 'Failed to update data.',
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
            APIHelper::responseFailed([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getContributions($uuid)
    {
        try {
            $dwelling = Dwelling::where('uuid', $uuid)->first(['uuid']);
            $contributions = $dwelling->contributions()->get(['contributions.uuid', 'amount', 'folio', 'comments', 'created_at', 'neighbor_uuid']);
            return response()->json($contributions, 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getNeighbors($uuid)
    {
        try {
            $dwelling = Dwelling::where('uuid', $uuid)->first();
            $neighbors = $dwelling->neighbors()->get(['uuid', 'firstname', 'lastname', 'phone_number']);
            return response()->json($neighbors, 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
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
            APIHelper::responseFailed([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getPeriods($uuid)
    {
        try {
            $dwelling = Dwelling::where('uuid', $uuid)->first();
            // obtener los periodos de la vivienda ordenados por año (campo: year) y mes (campo: month -> 01, 02, 03, ..., 12)
            $periods = $dwelling->periods()->orderBy('year', 'desc')->orderBy('month', 'desc')->get(['uuid', 'year', 'month', 'status', 'amount']);
            return response()->json($periods, 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getPendingPeriods($uuid)
    {
        try {
            $dwelling = Dwelling::where('uuid', $uuid)->first();
            // obtener los periodos de la vivienda que tengan el estatus pendiente
            $periods = $dwelling->periods()->where('status', 'pending')->get(['uuid', 'year', 'month', 'status', 'amount']);
            return response()->json($periods, 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function storePeriod(Request $request, $uuid)
    {
        try {
            $dwelling = Dwelling::where('uuid', $uuid)->first();

            if (!$dwelling) {
                return response()->json([
                    'message' => 'No se encontró la vivienda con el uuid proporcionado.'
                ], 404);
            }

            // validar si el periodo ya existe
            $period = Period::where('year', $request->get('year'))
                ->where('month', $request->get('month'))
                ->where('dwelling_uuid', $uuid)
                ->first();

            if ($period) {
                return response()->json([
                    'message' => $period->status === 'finalized' ? 'El periodo ya fue finalizado.' : 'El periodo ya existe.'
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
            APIHelper::responseFailed([
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

    public function update(Request $request, $uuid)
    {
        try {
            $dwelling = Dwelling::where('uuid', $uuid)->first();
            $dwelling->update($request->all());

            return response()->json($dwelling, 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
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

            APIHelper::responseFailed([
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
            APIHelper::responseFailed([
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
            ], 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
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
}