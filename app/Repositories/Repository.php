<?php

namespace App\Repositories;

class Repository
{
    public $model;

    public function all($where = [])
    {
        // validar que el where solo tenga valores válidos que coincidan con los campos de la tabla
        $fillable = $this->model->getFillable();

        $where = array_filter($where, function ($key) use ($fillable) {
            return in_array($key, $fillable);
        }, ARRAY_FILTER_USE_KEY);

        // Realiza una consulta where tomando el modelo que se asigna al momento de heredar está clase padre.
        return $this->model::where($where)->get();
    }

    public function find($uuid)
    {
        return $this->model::where('uuid', $uuid)->first();
    }

    public function create($data)
    {
        return $this->model::create($data);
    }

    public function update($uuid, $data)
    {
        $register = $this->model::where('uuid', $uuid)->first();
        $register->update($data);
        return $register;
    }

    public function delete($uuid)
    {
        $register = $this->model::where('uuid', $uuid)->first();
        if (is_null($register)) return null;
        return $register->delete();
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
}
