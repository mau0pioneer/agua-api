<?php

namespace App\Repositories;

use App\Models\AccessDwelling;

class AccessDwellingRepository extends Repository
{
  public function __construct(AccessDwelling $accessDwelling)
  {
    $this->model = $accessDwelling;
  }
}
