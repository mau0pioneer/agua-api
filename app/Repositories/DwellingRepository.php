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

  public function changeInhabited($uuid, $inhabited)
  {
    try {
      $dwelling = $this->find($uuid);
      $dwelling->inhabited = $inhabited ? $inhabited : !$dwelling->inhabited;
      $dwelling->save();
      return $dwelling;
    } catch (\Exception $ex) {
      $this->logError($ex);
      throw $ex;
    }
  }

  public function getContributions($uuid)
  {
    try {
      $dwelling = $this->find($uuid);
      $contributions = $dwelling->contributions()
        ->orderBy('created_at', 'desc')
        ->get();
      return $contributions;
    } catch (\Exception $ex) {
      $this->logError($ex);
      throw $ex;
    }
  }

  public function getNeighbors($uuid)
  {
    try {
      $dwelling = $this->find($uuid);
      $neighbors = $dwelling->neighbors()->get();
      return $neighbors;
    } catch (\Exception $ex) {
      $this->logError($ex);
      throw $ex;
    }
  }

  public function getPeriods($uuid)
  {
    try {
      $dwelling = $this->find($uuid);
      $periods = $dwelling->periods()
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();
      return $periods;
    } catch (\Exception $ex) {
      $this->logError($ex);
      throw $ex;
    }
  }

  public function getPendingPeriods($uuid)
  {
    try {
      $dwelling = $this->find($uuid);
      $periods = $dwelling->periods()
        ->where('status', 'pending')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();
      return $periods;
    } catch (\Exception $ex) {
      $this->logError($ex);
      throw $ex;
    }
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
