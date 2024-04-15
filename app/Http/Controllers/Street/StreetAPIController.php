<?php

namespace App\Http\Controllers\Street;

use App\Http\Controllers\APIController;
use App\Models\Street;
use App\Repositories\StreetRepository;
use Illuminate\Http\Request;

class StreetAPIController extends APIController
{
    public function __construct(StreetRepository $streetRepository)
    {
        $this->repository = $streetRepository;
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $streets = $this->repository->getByName($query);
        return response()->json($streets);
    }

    public function getDwellings($uuid)
    {
        $dwellings = $this->repository->getDwellings($uuid);
        return response()->json($dwellings);
    }

    public function getStreeNumbers($uuid)
    {
        $street = Street::where('uuid', $uuid)->first();
        $streetNumbers = $street->dwellings()->select('street_number')->distinct()->get();
        return response()->json($streetNumbers);
    }
}
