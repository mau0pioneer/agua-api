<?php

namespace App\Http\Controllers;

use App\Helpers\APIHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// Clase padre de todos los controladores de la API REST de Laravel
class APIController extends Controller
{
    // establecer el repositorio que se utilizará en el controlador
    protected $repository;

    // funcion para devolver todos los registros usando el repositorio
    public function index(Request $request)
    {
        try {
            $data = $this->repository->all($request->all(), $request->get('white_list') ?? []);
            // devolver los datos en formato JSON
            return response()->json($data, 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // funcion para devolver un registro por su uuid usando el repositorio
    public function show($uuid)
    {
        try {
            $data = $this->repository->find($uuid);

            if (is_null($data)) {
                APIHelper::responseFailed([
                    'message' => 'Data not found.',
                ], 404);
            }

            // devolver los datos en formato JSON
            return response()->json($data, 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
                'message' => 'Failed to get data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $this->validateRules($request);

        try {
            $data = $this->getFormattedData($request->all());
            // crear el registro en la base de datos
            $register = $this->repository->create($data);
            return response()->json($register, 201);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
                'message' => 'Failed to create data.',
            ], 500);
        }
    }

    public function update(Request $request, $uuid)
    {
        $this->validateRules($request, $uuid);
        try {
            $data = $this->getFormattedData($request->all());
            // actualizar el registro en la base de datos
            $register = $this->repository->update($uuid, $data);
            // devolver los datos en formato JSON
            return response()->json($register, 200);
        } catch (\Exception $e) {
            APIHelper::responseFailed([
                'message' => 'Failed to update data.',
            ], 500);
        }
    }

    protected function validateRules($request, $uuid = null)
    {
        $rules = $this->repository->getRules() ?? [];
        if (empty($rules)) return;

        $rules['uuid'] = is_null($uuid) ? 'nullable|unique:' . $this->repository->getTable().',uuid' : 'required|exists:' . $this->repository->getTable() . ',uuid';

        if (!is_null($uuid)) $request->merge(['uuid' => $uuid]);

        // validar los datos de entrada
        $validator = Validator::make($request->all(), $rules);
        // comprobar si la validación falla
        if ($validator->fails()) {
            // devolver los errores en formato JSON
            APIHelper::responseFailed([
                'message' => 'Failed to create data.',
                'errors' => $validator->errors()
            ], 422);
        }
    }

    protected function getFormattedData($data)
    {
        // recorrer y formatear los datos de entrada quitando los espacios en blanco y los strings convertirlos a minúsculas
        foreach ($data as $key => $value) {
            $data[$key] = is_string($value) ? strtolower(trim($value)) : $value;
        }
        return $data;
    }
}
