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
    $register = new $this->model([
      'folio' => $folio
    ]);
    
    try {
      $register = Contribution::where('folio', 'like', '%'.$folio.'%')->first();
    } catch (\Exception $e) {
      $this->logError($e);
      throw new \Exception('Ocurrió un error inesperado.');
    }

    return $register;
  }
}
