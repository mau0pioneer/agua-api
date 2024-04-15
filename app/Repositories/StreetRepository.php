<?php

namespace App\Repositories;

use App\Models\Street;

class StreetRepository extends Repository
{
  public function __construct(Street $street)
  {
    $this->model = $street;
  }

  public function getByName($name)
  {
    return $this->model->where('name', 'like', "%{$name}%")->get();
  }

  public function getDwellings($uuid)
  {
    $street = $this->find($uuid);
    return $street->dwellings()->get();
  }
}
