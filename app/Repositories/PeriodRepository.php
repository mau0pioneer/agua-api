<?php

namespace App\Repositories;

use App\Models\Period;

class PeriodRepository extends Repository
{
  public function __construct(Period $period)
  {
    $this->model = $period;
  }
}
