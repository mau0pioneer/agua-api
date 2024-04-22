<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function downloadLog()
    {
        $path = storage_path('logs/laravel.log');
        return response()->download($path);
    }
}
