<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function downloadLog()
    {
        $path = storage_path('logs/laravel.log');
        return response()->download($path);
    }

    public function report()
    {
        $contributions = Contribution::all();

        $data = [];

        foreach ($contributions as $contribution) {
            // date format 'Y-m-d'
            $date = date('Y-m-d', strtotime($contribution->created_at));

            if (!$contribution->collector) {
                continue;
            }

            // validar que el date sea menor a la fecha actual
            if ($date > '2024-04-01') {
                continue;
            }

            $data[] = [
                'FOLIO' => $contribution->folio,
                'FECHA' => $date,
                'CANTIDAD' => $contribution->amount,
                'COLLECTOR' => $contribution->collector->name,
            ];
        }

        return  $data;
    }
}
