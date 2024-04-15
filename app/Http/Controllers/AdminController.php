<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Dwelling;
use App\Models\Neighbor;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $total_neighbors = Neighbor::count();
        $total_contributions = Contribution::where('status', 'finalized')->count();
        $total_dwellings = Dwelling::count();

        $total_amount_contributions = Contribution::where('status', 'finalized')->sum('amount');

        return view('admin.index', compact('total_neighbors', 'total_contributions', 'total_dwellings', 'total_amount_contributions'));
    }

    public function contributions()
    {
        // obtener las contribuciones limitadas a 10 y por paginas. tomar la pagina que venga del request
        $page = request('page', 1);
        $contributions = [];

        try {
            // obtener las contribuciones
            $contributions = Contribution::paginate(10, ['*'], 'page', $page);
            // obtener el numero de paginas existentes
            $pages = $contributions->lastPage();
        } catch (\Throwable $th) {
            $pages = 0;
        }

        return view('admin.contributions', compact('contributions', 'pages', 'page'));
    }

    public function neighbors()
    {
        // obtener las contribuciones limitadas a 10 y por paginas. tomar la pagina que venga del request
        $page = request('page', 1);

        // obtener las contribuciones
        $neighbors = Neighbor::paginate(10, ['*'], 'page', $page);

        // obtener el numero de paginas existentes
        $pages = $neighbors->lastPage();

        return view('admin.neighbors', compact('neighbors', 'pages', 'page'));
    }

    public function showNeighbor($uuid)
    {
        $neighbor = Neighbor::where('uuid', $uuid)->first();
        if (!$neighbor) return redirect()->route('admin.neighbors')->with('error', 'Vecino no encontrado');
        return view('admin.show-neighbor', compact('neighbor'));
    }

    public function showContribution($uuid)
    {
        $contribution = Contribution::where('uuid', $uuid)->first();

        if (!$contribution) {
            return redirect()->route('admin.contributions')->with('error', 'Contribución no encontrada');
        }

        return view('admin.show-contribution', compact('contribution'));
    }

    public function dwellings()
    {
        // obtener las direcciones limitadas a 10 y por paginas. tomar la pagina que venga del request
        $page = request('page', 1);

        // obtener las direcciones
        $dwellings = [];

        try {
            // obtener las direcciones
            $dwellings = Dwelling::paginate(10, ['*'], 'page', $page);
            // obtener el numero de paginas existentes
            $pages = $dwellings->lastPage();
        } catch (\Throwable $th) {
            $pages = 0;
        }

        return view('admin.dwellings', compact('dwellings', 'pages', 'page'));
    }

    public function showDwelling($uuid)
    {
        $dwelling = Dwelling::where('uuid', $uuid)->first();

        if (!$dwelling) {
            return redirect()->route('admin.dwellings')->with('error', 'Dirección no encontrada');
        }

        return view('admin.show-dwelling', compact('dwelling'));
    }
}
