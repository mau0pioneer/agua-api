<?php

namespace App\Repositories;

use App\Models\Neighbor;

class NeighborRepository extends Repository
{
  public function __construct(Neighbor $neighbor)
  {
    $this->model = $neighbor;
  }
}
