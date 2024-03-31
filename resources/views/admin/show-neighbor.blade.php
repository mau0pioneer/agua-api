@extends('layouts.app')
@section('title', 'Vecinos')
@section('content')
@include('includes.breadcrumb', ['title' => 'Vecino'])

<h1 class="text-3xl text-black pb-6 capitalize">
  {{ $neighbor->firstname }} {{ $neighbor->lastname }}
</h1>
<div class="w-full">
  <p class="text-xl pb-3 flex items-center">
    Datos del vecino
  </p>

  <div class="bg-white p-3 rounded-lg">
    {{-- phone_number: format phone --}}
    <p class="text-lg">
      <i class="fas fa-phone-alt mr-3"></i>
      @if ($neighbor->phone_number)
        {{ $neighbor->phone_number }}
      @else
        <span class="text-gray-400 italic">Sin teléfono</span>
      @endif
    </p>

    {{-- comments --}}
    <p class="text-lg">
      <i class="fas fa-comment mr-3"></i>
      @if ($neighbor->comments)
        {{ $neighbor->comments }}
      @else
        <span class="text-gray-400 italic">Sin comentarios</span>
      @endif
    </p>
  </div>

  <div class="mt-10">
    <h2 class="text-2xl text-black pb-4">
      Viviendas
    </h2>

    {{-- cards: neighbor->dwellings --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach ($neighbor->dwellings as $dwelling)
        <a href="{{ route('admin.dwellings.show', $dwelling->uuid) }}" class="bg-white p-3 rounded-lg">
          <div class="text-lg capitalize">
            <i class="fas fa-home mr-3"></i>
            {{ $dwelling->street->name }} {{ $dwelling->street_number }} {{ $dwelling->interior_number }}
          </div>
        </a>
      @endforeach

  </div>

  <div class="mt-10">
    {{-- h2 --}}
    <h2 class="text-2xl text-black pb-4">
      Contribuciones
    </h2>

    @php
      $contributions = $neighbor->contributions;
    @endphp
    @include('contribution.table', [
      'view_comments' => true,
      'hidden_neighbor' => false,
    ])
  </div>

</div>
@endsection