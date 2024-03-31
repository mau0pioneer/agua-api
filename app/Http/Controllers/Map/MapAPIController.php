<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Models\Dwelling;
use Illuminate\Http\Request;

/*
  "4": "#FDB600",
  "3": "#1FAFB5",
  "2": "#FC0E93",
  "1": "#B188E4" 
*/
class MapAPIController extends Controller
{
    public function getContributions(Request $request)
    {
        $collector_uuid = $request->get('collector_uuid');
        $dwellings = Dwelling::with([
            'contributions' => function ($query) use ($collector_uuid) {
                $query->select(['uuid', 'collector_uuid', 'dwelling_uuid']);
            },
            'periods' => function ($query) {
                $query->where('status', 'pending')->select(['uuid', 'dwelling_uuid']);
            }
        ])->get(['coordinates_uuid', 'uuid']);
        $contributions = [];

        foreach ($dwellings as $dwelling) {
            $pendings_periods = $dwelling->periods->count() > 0;
            $color = $pendings_periods ? 'yellow' : '#00d407';

            if ($dwelling->contributions->count() === 0) $color = 'gray';

            $contributions[] = [
                'color' => $color,
                'coordinates_uuid' => $dwelling->coordinates_uuid,
                'uuid' => $dwelling->uuid
            ];
        }

        return response()->json($contributions);
    }

    public function getNoneContributions()
    {
        $dwellings = Dwelling::with([
            'contributions' => function ($query) {
                $query->select(['uuid', 'collector_uuid', 'dwelling_uuid']);
            }
        ])->get(['coordinates_uuid', 'uuid']);
        $contributions = [];

        foreach ($dwellings as $dwelling) {
            if ($dwelling->contributions->count() === 0) $contributions[] = [
                'color' => 'red',
                'coordinates_uuid' => $dwelling->coordinates_uuid,
                'uuid' => $dwelling->uuid
            ];
        }

        return response()->json($contributions);
    }

    public function getInhabiteds(Request $request)
    {
        $inhabited = $request->get('inhabited');
        $dwellings = Dwelling::where('inhabited', $inhabited !== null ? '=' : '!=', $inhabited)->get(['coordinates_uuid', 'uuid', 'inhabited', 'type']);
        return response()->json($dwellings);
    }
}
