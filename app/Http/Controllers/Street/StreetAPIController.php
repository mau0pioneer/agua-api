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
        $streets = Street::where("name", "like", "%{$query}%")->get();
        return response()->json($streets);
    }

    public function getDwellings($uuid)
    {
        $street = Street::where('uuid', $uuid)->first();
        $dwellings = $street->dwellings()->get();
        return response()->json($dwellings);
    }

    public function getStreeNumbers($uuid)
    {
        // obtener las viviendas de la calle con el uuid de la calle y mostrar solo los numeros de las viviendas sin repetir
        $street = Street::where('uuid', $uuid)->first();
        $streetNumbers = $street->dwellings()->select('street_number')->distinct()->get();

        return response()->json($streetNumbers);
    }
}
