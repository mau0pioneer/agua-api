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

  public function getLastPeriod($uuid)
  {
    try {
      $dwelling = $this->find($uuid);
      $period = $dwelling->periods()
        ->where('status', 'paid')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->first(['year', 'month', 'dwelling_uuid', 'status']);
      return $period;
    } catch (\Exception $ex) {
      $this->logError($ex);
      throw $ex;
    }
  }
}
