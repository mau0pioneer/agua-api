<?php

namespace App\Http\Controllers\Collector;

use App\Helpers\APIHelper;
use App\Http\Controllers\APIController;
use App\Models\Collector;
use App\Models\Contribution;
use App\Models\Dwelling;
use App\Models\Period;
use App\Repositories\CollectorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectorAPIController extends APIController
{
    public function __construct(CollectorRepository $collectorRepository)
    {
        $this->repository = $collectorRepository;
    }

    public function storeContribution(Request $request)
    {
        $dwelling_uuid = $request->get('dwelling_uuid');
        $neighbor_uuid = $request->get('neighbor_uuid');
        $collector_email = $request->get('collector_email');
        $periods = $request->get('periods');
        $amount = $request->get('amount');
        $concept = $request->get('concept');
        $comments = $request->get('comments');
        $contribution_uuid = $request->get('contribution_uuid');
        $collector_uuid = $request->get('collector_uuid');

        try {
            DB::beginTransaction();

            // VALIDACIONES

            // validar que la vivienda exista
            $dwelling = Dwelling::where('uuid', $dwelling_uuid)->first();
            if (!$dwelling) {
                return response()->json([
                    'message' => 'Dwelling not found.'
                ], 404);
            }

            // validar que el colector exista
            $collector = Collector::where('email', $collector_email)->first();
            if (!$collector) {
                return response()->json([
                    'message' => 'Collector not found.'
                ], 404);
            }

            if($collector_uuid) {
                $collector = Collector::where('uuid', $collector_uuid)->first();
            }

            // validar que la aportación exista
            $contribution = Contribution::where('uuid', $contribution_uuid)->first();
            if (!$contribution) {
                return response()->json([
                    'message' => 'Contribution not found.'
                ], 404);
            }

            $contribution->status = 'finalized';
            $contribution->amount = $amount;
            $contribution->comments = $comments;
            $contribution->collector_uuid = $collector->uuid;
            $contribution->dwelling_uuid = $dwelling->uuid;
            $contribution->neighbor_uuid = $neighbor_uuid;
            $contribution->save();

            foreach ($periods as $period_uuid) {
                $period = Period::where('uuid', $period_uuid)->first();
                $period->status = 'paid';  
                $period->amount = 0;
                $period->save();
            }

            DB::commit();

            return response()->json(
                compact('dwelling', 'collector', 'contribution', 'periods', 'amount', 'concept', 'comments'),
                200
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            APIHelper::responseFailed([
                'message' => 'Failed to get data.',
            ], 500);
        }
    }
}
