<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;

class Repository
{
    public $model;

    public function all($where = [])
    {
        try {
            // Realiza una consulta where tomando el modelo que se asigna al momento de heredar está clase padre.
            return $this->model::where($where)->get();
        } catch (\Exception $ex) {
            $this->logError($ex);
            throw $ex;
        }
    }

    public function find($uuid)
    {
        try {
            // Obtiene un registro a partir del uuid
            return $this->model::where('uuid', $uuid)->first();
        } catch (\Exception $ex) {
            $this->logError($ex);
            throw $ex;
        }
    }

    public function create($data)
    {
        try {
            // Crea un nuevo registro a partir de los datos enviados
            return $this->model::create($data);
        } catch (\Exception $ex) {
            $this->logError($ex);
            throw $ex;
        }
    }

    public function update($uuid, $data)
    {
        try {
            $register = $this->model::where('uuid', $uuid)->first();
            $register->update($data);
            return $register;
        } catch (\Exception $ex) {
            $this->logError($ex);
            throw $ex;
        }
    }

    public function delete($id)
    {
        $result = false;

        try {
            $register = $this->model->find($id);
            $result = $register->id && $register->delete();
            return $result;
        } catch (\Exception $ex) {
            $this->logError($ex);
            throw $ex;
        }
    }

    public function getTable()
    {
        return $this->model->getTable();
    }

    public function getRules()
    {
        return $this->model::$rules;
    }

    public function getRulesMessages()
    {
        return $this->model::$rules_messages;
    }

    public function getFillable()
    {
        $instanceModel = new $this->model;
        return $instanceModel->getFillable();
    }

    public function getModel()
    {
        $instanceModel = new $this->model;
        return $instanceModel;
    }

    protected function logError($ex)
    {
        Log::error($this->model::class . ' -' . ' all - line:' . $ex->getLine() . ' - ' . $ex->getMessage());
    }
}
