<?php

namespace App\Repositories;

use App\Services\SendGridService;
use Illuminate\Support\Facades\Log;

class Repository
{
    public $model;

    public function all($where = [])
    {
        try {
            // validar que el where solo tenga valores válidos que coincidan con los campos de la tabla
            $fillable = $this->model->getFillable();
            
            $where = array_filter($where, function ($key) use ($fillable) {
                return in_array($key, $fillable);
            }, ARRAY_FILTER_USE_KEY);

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

    public function delete($uuid)
    {
        try {
            $register = $this->model::where('uuid', $uuid)->first();
            if (is_null($register)) return null;
            return $register->delete();
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
        $sendGridServicio = new SendGridService();
        $sendGridServicio->sendEmail('mtz0mau2002@gmail.com', 'Error en el sistema', $this->model::class . ' -' . ' all - line:' . $ex->getLine() . ' - ' . $ex->getMessage());
    }
}
