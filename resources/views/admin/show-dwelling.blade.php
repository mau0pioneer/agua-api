@extends('layouts.app')
@section('title', 'Vecinos')
@section('content')
@include('includes.breadcrumb', ['title' => 'Vivienda'])
<h1 class="text-3xl text-black pb-6 capitalize">
  {{ $dwelling->street->name }} {{ $dwelling->street_number }} {{ $dwelling->interior_number }}
</h1>
<div class="w-full">
  <p class="text-xl pb-3 flex items-center">
    Datos de la vivienda
  </p>

  <div class="grid">
    <div class="bg-white p-3 rounded-lg">
      {{-- access_code --}}
      <p class="text-lg">
        Código de acceso: <a class="text-blue-500 underline" href="https://agua-recibos.web.app?accessCode={{ $dwelling->access_code }}" target="_blank">{{ $dwelling->access_code }}</a>
      </p>
    </div>
  </div>

  <div class="mt-10">
    <h2 class="text-2xl text-black pb-4">
      Vecinos
    </h2>

    @if ($dwelling->neighbors->count() === 0)
      <p class="text-base italic text-gray-400">
        No hay vecinos registrados.
      </p>
    @endif

    {{-- cards: neighbor->dwellings --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach ($dwelling->neighbors as $neighbor)
        <a href="{{ route('admin.neighbors.show', $neighbor->uuid) }}" class="bg-white p-3 rounded-lg">
          <p class="text-lg capitalize">
            {{ $neighbor->firstname }} {{ $neighbor->lastname }}
          </p>
          <p class="text-lg">
            {{ $neighbor->phone_number }}
          </p>
        </a>
      @endforeach
      
  </div>

  <div class="mt-10">
    {{-- h2 --}}
    <h2 class="text-2xl text-black pb-4">
      Contribuciones
    </h2>

    @php
      $contributions = $dwelling->contributions;
    @endphp
    @include('contribution.table', [
      'view_comments' => true,
      'hidden_neighbor' => false,
    ])
  </div>
</div>
@endsection