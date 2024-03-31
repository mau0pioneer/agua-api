<?php

namespace App\Http\Controllers\DwellingNeighbor;

use App\Http\Controllers\APIController;
use App\Repositories\DwellingNeighborRepository;
use Illuminate\Http\Request;

class DwellingNeighborAPIController extends APIController
{
    public function __construct(DwellingNeighborRepository $dwellingNeighborRepository)
    {
        $this->repository = $dwellingNeighborRepository;
    }
}
