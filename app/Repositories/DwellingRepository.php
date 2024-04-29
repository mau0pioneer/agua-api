<?php

namespace App\Repositories;

use App\Models\Dwelling;

class DwellingRepository extends Repository
{
  public function __construct(Dwelling $dwelling)
  {
    $this->model = $dwelling;
  }

  public function changeInhabited($uuid, $inhabited)
  {
    $dwelling = $this->find($uuid);
    $dwelling->inhabited = $inhabited ? $inhabited : !$dwelling->inhabited;
    $dwelling->save();
    return $dwelling;
  }

  public function getContributions($uuid)
  {
    $dwelling = $this->find($uuid);
    $contributions = $dwelling->contributions()
      ->where('status', 'paid')
      ->orderBy('created_at', 'desc')
      ->get();
    return $contributions;
  }

  public function getNeighbors($uuid)
  {
    $dwelling = $this->find($uuid);
    $neighbors = $dwelling->neighbors()->get();
    return $neighbors;
  }

  public function getPeriods($uuid)
  {
    $dwelling = $this->find($uuid);
    $periods = $dwelling->periods()
      ->orderBy('year', 'desc')
      ->orderBy('month', 'desc')
      ->get();
    return $periods;
  }

  public function getPendingPeriods($uuid)
  {
    $dwelling = $this->find($uuid);
    $periods = $dwelling->periods()
      ->where('status', 'pending')
      ->orderBy('year', 'desc')
      ->orderBy('month', 'desc')
      ->get();
    return $periods;
  }

  public function getLastPeriod($uuid)
  {
    $dwelling = $this->find($uuid);
    $period = $dwelling->periods()
      ->where('status', 'paid')
      ->orderBy('year', 'desc')
      ->orderBy('month', 'desc')
      ->first(['year', 'month', 'dwelling_uuid', 'status']);
    return $period;
  }
}
