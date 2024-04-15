<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\APIController;
use App\Repositories\CollectorRepository;
use App\Repositories\ContributionRepository;
use App\Repositories\PeriodRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectorAPIController extends APIController
{
    public function __construct(CollectorRepository $collectorRepository)
    {
        $this->repository = $collectorRepository;
    }

    public function storeContribution(
        Request $request,
        ContributionRepository $contributionRepository,
        PeriodRepository $periodRepository
    ) {
        try {
            $errors = $this->validateRules($request, null, [
                'dwelling_uuid' => 'required|uuid|exists:dwellings,uuid',
                'neighbor_uuid' => 'required|uuid|exists:neighbors,uuid',
                'collector_email' => 'required|email|exists:collectors,email',
                'periods' => 'required|array',
                'amount' => 'required|numeric',
                'concept' => 'required|string',
                'comments' => 'nullable|string',
                'contribution_uuid' => 'required|uuid|exists:contributions,uuid',
                'collector_uuid' => 'nullable|uuid|exists:collectors,uuid'
            ]);
            if ($errors) return response()->json($errors, 400);

            DB::beginTransaction();

            $collector =
                $request->collector_uuid
                ? $this->repository->find($request->collector_uuid)
                : $this->repository->findByEmail($request->collector_email);

            $contribution = $contributionRepository->find($request->contribution_uuid);
            $contribution->status = 'finalized';
            $contribution->amount = $request->amount;
            $contribution->collector_uuid = $collector->uuid;
            $contribution->dwelling_uuid = $request->dwelling_uuid;
            $contribution->neighbor_uuid = $request->neighbor_uuid;

            $periods = [];
            foreach ($request->periods as $period_uuid) {
                $period = $periodRepository->find($period_uuid);
                $period->status = 'paid';
                $period->amount = 0;
                $period->save();
                $periods[] = $period;
            }

            $contribution->comments = $request->concept === 'period' ? (
                'Pago de ' . (count($periods) === 1 ? 'periodo' : 'periodos') . ': ' . implode(', ', array_map(function ($period) {
                    return $period->getMonth() . '-' . $period->year;
                }, $periods))
            ) : 'Reconexión';

            $contribution->save();

            DB::commit();
            return response()->json($contribution, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
