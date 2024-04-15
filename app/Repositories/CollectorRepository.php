<?php

namespace App\Repositories;

use App\Models\Collector;

class CollectorRepository extends Repository
{
  public function __construct(Collector $collector)
  {
    $this->model = $collector;
  }

  public function findByEmail($email)
  {
    return $this->model->where('email', $email)->first();
  }
}
