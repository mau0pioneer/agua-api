@extends('layouts.app')
@section('title', 'Inicio')
@section('content')
<h1 class="text-3xl text-black pb-6">Inicio</h1>
<!-- Content goes here! 😁 -->

{{-- cards with content: total contributions, total dwellings, total neighbors --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-blue-400 text-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold text-center">Total de aportaciones</h2>
        <p class="text-4xl font-bold text-center">
          {{ $total_contributions }}
        </p>
    </div>
    <div class="bg-green-500 text-white shadow-lg rounded-lg p-8">
      <h2 class="text-2xl font-bold text-center">Cantidad ingresada</h2>
      <p class="text-4xl font-bold text-center">
        ${{ number_format($total_amount_contributions, 2) }}
      </p>
    </div>
    <div class="bg-orange-500 text-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold text-center">Total de viviendas</h2>
        <p class="text-4xl font-bold text-center">
          {{ $total_dwellings }}
        </p>
    </div>
    <div class="bg-pink-500 text-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold text-center">Total de vecinos</h2>
        <p class="text-4xl font-bold text-center">
          {{ $total_neighbors }}
        </p>
    </div>
</div>
@endsection