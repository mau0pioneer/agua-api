<?php

namespace App\Http\Controllers\AccessDwelling;

use App\Http\Controllers\APIController;
use App\Repositories\AccessDwellingRepository;
use Illuminate\Http\Request;

class AccessDwellingAPIController extends APIController
{
    public function __construct(AccessDwellingRepository $accessDwellingRepository)
    {
        $this->repository = $accessDwellingRepository;
    }
}
