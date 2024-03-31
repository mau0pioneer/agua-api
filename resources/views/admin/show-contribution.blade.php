@extends('layouts.app')
@section('title', 'Vecinos')
@section('content')
@include('includes.breadcrumb', ['title' => 'Recibo'])
<div class="p-5 bg-white rounded-xl w-[350px]">
  <h1 class="text-3xl text-black capitalize">
    {{ $contribution->folio }}
  </h1>
  <div class="w-full">
    <p class="text-xl flex items-center">
      Datos del recibo
    </p>

    <div class="bg-white p-3 rounded-lg">
      <p class="text-lg capitalize">
        <i class="fas fa-calendar-alt mr-3"></i>
        {{ $contribution->created_at->isoFormat('dddd D [de] MMMM [de] YYYY') }}
      </p>

      <p class="text-lg">
        <i class="fas fa-money-bill-wave mr-3"></i>
        ${{ number_format($contribution->amount, 2) }}
      </p>

      <p class="text-lg">
        <i class="fas fa-user mr-3"></i>
        Emitido por: {{ $contribution->collector->name }}
      </p>
    </div>
  </div>
  
</div>
@endsection