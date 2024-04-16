<?php

namespace App\Http\Controllers;

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
            $data = $this->repository->all($request->all());
            // devolver los datos en formato JSON
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
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

            if (is_null($data->id)) return response()->json([
                'message' => 'Data not found.',
                'error' => true
            ], 404);

            // devolver los datos en formato JSON
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
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
            return response()->json([
                'message' => 'Failed to save data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $uuid)
    {
        $this->validateRules($request, $uuid);
        try {
            $data = $this->getFormattedData($request->all());

            $error = $this->validateRules($request, $uuid);
            if ($error) return $error;

            // actualizar el registro en la base de datos
            $register = $this->repository->update($uuid, $data);
            // devolver los datos en formato JSON
            return response()->json($register, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($uuid)
    {
        try {
            // eliminar el registro de la base de datos
            $deleted = $this->repository->delete($uuid);

            // validar si el registro no se encontró
            if (is_null($deleted)) {
                return response()->json([
                    'message' => 'Data not found.',
                    'error' => true
                ], 404);
            }

            // devolver los datos en formato JSON
            return response()->json([
                'message' => 'Data deleted successfully.',
                'data' => $deleted
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    protected function validateRules($request, $uuid = null, $rules = [], $table = '')
    {
        $rules = !empty($rules) ? $rules : $this->repository->getRules();
        $table = !empty($table) ? $table : $this->repository->getTable();

        // si el uuid es nulo, entonces el campo uuid es opcional y único
        $rules['uuid'] = is_null($uuid) ? 'exists|unique:' . $table . ',uuid' : 'required|nullable:' . $table . ',uuid';

        // si el uuid no es nulo, entonces se agrega al request
        if (!is_null($uuid)) $data['uuid'] = $uuid;

        // validar los datos de entrada
        $validator = Validator::make($request->all(), $rules);

        // comprobar si la validación falla
        if ($validator->fails()) {
            // devolver los errores en formato JSON
            return response()->json([
                'message' => 'Validation failed.',
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
