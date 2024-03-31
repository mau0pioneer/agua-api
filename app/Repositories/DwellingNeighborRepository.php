<?php

namespace App\Repositories;

use App\Models\DwellingNeighbor;

class DwellingNeighborRepository extends Repository
{
  public function __construct(DwellingNeighbor $dwellingNeighbor)
  {
    $this->model = $dwellingNeighbor;
  }
}
