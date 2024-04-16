<?php

namespace App\Http\Controllers\Period;

use App\Http\Controllers\APIController;
use App\Repositories\PeriodRepository;
use Illuminate\Http\Request;

class PeriodAPIController extends APIController
{
    public function __construct(PeriodRepository $periodRepository)
    {
        $this->repository = $periodRepository;
    }
}
