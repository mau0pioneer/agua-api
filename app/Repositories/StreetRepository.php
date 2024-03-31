<?php

namespace App\Repositories;

use App\Models\Street;

class StreetRepository extends Repository
{
  public function __construct(Street $street)
  {
    $this->model = $street;
  }
}
