<?php

namespace App\Repositories;

use App\Models\Contribution;

class ContributionRepository extends Repository
{
  public function __construct(Contribution $contribution)
  {
    $this->model = $contribution;
  }

  public function findByFolio($folio)
  {
    return Contribution::where('folio', 'like', '%' . $folio . '%')->first();
  }
}
