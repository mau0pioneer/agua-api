<?php

namespace App\Http\Controllers\Neighbor;

use App\Helpers\APIHelper;
use App\Http\Controllers\APIController;
use App\Models\Dwelling;
use App\Models\DwellingNeighbor;
use App\Models\Neighbor;
use App\Repositories\NeighborRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NeighborAPIController extends APIController
{
    public function __construct(NeighborRepository $neighborRepository)
    {
        $this->repository = $neighborRepository;
    }

    public function store(Request $request)
    {
        $this->validateRules($request);
        try {
            DB::beginTransaction();
            $data = $this->getFormattedData($request->all());
            $dwelling_uuid = $request->dwelling_uuid;

            // comprobar que la vivienda exista
            $dwelling = Dwelling::where('uuid', $dwelling_uuid)->first();
            if (!$dwelling) {
                return response()->json([
                    'message' => 'Error al obtener la vivienda.'
                ], 404);
            }

            $neighbor = Neighbor::firstOrNew([
                'phone_number' => $data['phone_number']
            ]);

            $neighbor->firstname = $data['firstname'];
            $neighbor->lastname = $data['lastname'];

            // crear el registro en la base de datos
            $neighbor->save();

            // asociar el vecino a la vivienda
            DwellingNeighbor::firstOrCreate([
                'dwelling_uuid' => $dwelling_uuid,
                'neighbor_uuid' => $neighbor->uuid
            ]);
            DB::commit();
            return response()->json($neighbor, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return response()->json([
                'message' => 'Error al guardar el vecino.'
            ], 500);
        }
    }

    public function update(Request $request, $uuid)
    {
        try {
            $data = $this->getFormattedData($request->all());

            $register = $this->repository->find($uuid);
            if (!$register) {
                return response()->json([
                    'message' => 'Record not found.'
                ], 404);
            }

            $phone_number = $data['phone_number'];

            $error = $this->validateRules($request, $uuid, [
                'phone_number' => 'required|string|min:10|max:10|regex:/^[0-9]{10}$/' . ($phone_number != $register->phone_number ? '|unique:neighbors,phone_number' : ''),
                'firstname' => 'required',
                'lastname' => 'required',
            ]);
            if ($error) return $error;

            // actualizar el registro en la base de datos
            $register = $this->repository->update($uuid, $data);
            // devolver los datos en formato JSON
            return response()->json($register, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to update record.'
            ], 500);
        }
    }

    public function getFullname($uuid)
    {
        try {
            $neighbor = Neighbor::where('uuid', $uuid)->first(['firstname', 'lastname', 'uuid']);
            return response()->json([
                'fullname' => $neighbor->firstname . ' ' . $neighbor->lastname
            ], 200);
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'message' => 'Failed to get data.'
            ], 500);
        }
    }

    public function getPhoneNumber($uuid)
    {
        try {
            $neighbor = Neighbor::where('uuid', $uuid)->first(['phone_number']);
            return response()->json([
                'phone_number' => $neighbor->phone_number
            ], 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to get data.'
            ], 500);
        }
    }

    public function getNeighbors(Request $request)
    {
        $neighbors = Neighbor::where('phone_number', '!=', null)->get();
        return view('neighbor.index', compact('neighbors'));
    }

    public function getAccessCode()
    {
        $data = Neighbor::all();

        $neighbors = [];
        foreach ($data as $neighbor) {
            $neighbors[] = [
                'uuid' => $neighbor->uuid,
                'fullname' => $neighbor->firstname . ' ' . $neighbor->lastname,
                'phone_number' => $neighbor->phone_number,
                'access_code' => $neighbor->contributions()->first()->dwelling->access_code,
                'dwelling' => $neighbor->contributions()->first()->dwelling->street->name . ' ' . $neighbor->contributions()->first()->dwelling->street_number . ' ' . $neighbor->contributions()->first()->dwelling->interior_number
            ];
        }

        return response()->json($neighbors, 200);
    }

    public function updatePhoneNumber(Request $request, $uuid)
    {
        try {
            $neighbor = Neighbor::where('uuid', $uuid)->first();

            $phone_number = $request->phone_number;

            // validar que el número de teléfono sea correcto
            if (!preg_match('/^[0-9]{10}$/', $phone_number)) {
                return response()->json([
                    'message' => 'Invalid phone number.'
                ], 400);
            }

            // validar que el telefono no exista en otro vecino
            $neighborExists = Neighbor::where('phone_number', $phone_number)->first();
            if ($neighborExists) {
                return response()->json([
                    'message' => 'Phone number already exists.'
                ], 400);
            }

            $neighbor->phone_number = $phone_number;
            $neighbor->save();

            return response()->json($neighbor, 200);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'message' => 'Failed to update phone number.'
            ], 500);
        }
    }

    public function getPhonesData()
    {
        $data = Neighbor::where('phone_number', '!=', null)->get();

        $neighbors = [];
        foreach ($data as $neighbor) {
            // excluir a los vecinos que su telefono empieza con 000
            if (substr($neighbor->phone_number, 0, 3) == '000') {
                continue;
            }
            $neighbors[] = $neighbor;
        }

        return response()->json([
            'total' => count($neighbors),
            'data' => $neighbors
        ], 200);
    }

    public function getNotPhones()
    {
        $data = Neighbor::where('phone_number', '!=', null)->get();

        $neighbors = [];
        foreach ($data as $neighbor) {
            // excluir a los vecinos que su telefono empieza con 000
            if (substr($neighbor->phone_number, 0, 3) == '000') {
                $neighbors[] = $neighbor;
            }
        }

        $data = Neighbor::where('phone_number', '=', null)->get();
        foreach ($data as $neighbor) {
            $neighbors[] = $neighbor;
        }

        // ordenar neighbors por su primer dwelling y la street_uuid de ese dwelling
        usort($neighbors, function ($a, $b) {
            // validar que el vecino tenga contribuciones y viviendas
            if (!$a->contributions()->first() || !$b->contributions()->first()) {
                return 0;
            }

            // obtener el primer dwelling de cada vecino
            $dwellingA = $a->contributions()->first()->dwelling;
            // obtener el primer dwelling de cada vecino
            $dwellingB = $b->contributions()->first()->dwelling;

            // si la calle es la misma, ordenar por el número de calle
            if ($dwellingA->street_uuid == $dwellingB->street_uuid) {
                // si el número de calle es el mismo, ordenar por el número de vivienda
                return $dwellingA->street_number <=> $dwellingB->street_number;
            }

            // si la calle no es la misma, ordenar por la calle
            return $dwellingA->street_uuid <=> $dwellingB->street_uuid;
        });

        return view('notphones', compact('neighbors'));
    }
}
