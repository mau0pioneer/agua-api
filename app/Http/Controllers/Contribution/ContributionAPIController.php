<?php

namespace App\Http\Controllers\Contribution;

use App\Http\Controllers\APIController;
use App\Repositories\ContributionRepository;
use Illuminate\Http\Request;

class ContributionAPIController extends APIController
{
    public function __construct(ContributionRepository $contributionRepository)
    {
        $this->repository = $contributionRepository;
    }

    public function show($uuid)
    {
        try {
            $register = $this->repository->find($uuid);

            // validar si existe el folio
            if (is_null($register->id)) {
                return response()->json([
                    'message' => 'No se encontró el folio.',
                ], 404);
            }

            // validar si el folio ya fue finalizado
            if ($register->status === 'finalized') {
                return response()->json([
                    'message' => 'El folio ' . $register->folio . ' ya fue finalizado. Intente con otro folio.'
                ], 404);
            }

            // devolver los datos en formato JSON
            return response()->json($register, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error inesperado.',
            ], 500);
        }
    }

    public function showByFolio($folio)
    {
        try {
            // Validar que el folio venga con el formato correcto (XXXX-TA-XXXX)
            if (!$this->repository->model::validateFolio($folio)) {
                return response()->json([
                    'message' => 'El folio no tiene el formato correcto.',
                ], 400);
            }

            $register = $this->repository->findByFolio($folio);

            // validar si existe el folio
            if (empty($register->id)) {
                return response()->json([
                    'message' => 'No se encontró el folio.',
                ], 404);
            }

            return response()->json($register, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error inesperado.',
            ], 500);
        }
    }
}
