<?php

namespace App\Repositories;

use App\Models\Signature;

class SignatureRepository extends Repository
{
  public function __construct(Signature $signature)
  {
    $this->model = $signature;
  }
}
