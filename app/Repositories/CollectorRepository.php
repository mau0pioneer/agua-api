<?php

namespace App\Repositories;

use App\Models\Collector;

class CollectorRepository extends Repository
{
  public function __construct(Collector $collector)
  {
    $this->model = $collector;
  }

  
}
