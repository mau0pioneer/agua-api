<?php

namespace App\Repositories;

use App\Models\Dwelling;

class DwellingRepository extends Repository
{
  public function __construct(Dwelling $dwelling)
  {
    $this->model = $dwelling;
  }

  public function findTitleByUuid($uuid)
  {
    return $this->model->where('uuid', $uuid)->first();
  }
}
