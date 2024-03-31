<?php

namespace App\Http\Controllers\Signature;

use App\Http\Controllers\APIController;
use App\Repositories\SignatureRepository;
use Illuminate\Http\Request;

class SignatureAPIController extends APIController
{
    public function __construct(SignatureRepository $signatureRepository)
    {
        $this->repository = $signatureRepository;
    }
}
