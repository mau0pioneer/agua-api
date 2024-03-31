@extends('layouts.app')
@section('title', 'Vecinos')
@section('content')
<h1 class="text-3xl text-black pb-6">Vecinos</h1>
<div class="w-full">
  <p class="text-xl pb-3 flex items-center">
      <i class="fas fa-list mr-3"></i> Listado de vecinos
  </p>

  @include('includes.messages')

  <div class="bg-white overflow-auto">
      <table class="min-w-full bg-white">
          <thead class="bg-gray-800 text-white">
              <tr>
                  <th class="text-left py-3 px-4 uppercase font-semibold text-sm">ID</th>
                  <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nombre</th>
                  <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Teléfono</th>
                  <th class="text-left py-3 px-4 uppercase font-semibold text-sm" colspan="2">Comentarios</th>
                  <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Acciones</th>
              </tr>
          </thead>
          <tbody class="text-gray-700">
              @foreach ($neighbors as $neighbor)
                <tr class="hover:bg-gray-100">
                    <td class="text-left py-3 px-4">{{ $neighbor->id }}</td>
                    <td class="text-left py-3 px-4 capitalize">{{ $neighbor->firstname }} {{ $neighbor->lastname }}</td>
                    <td class="text-left py-3 px-4">{{ $neighbor->phone_number }}</td>
                    <td class="text-left py-3 px-4" colspan="2">
                      @if ($neighbor->comments)
                        {{ $neighbor->comments }}
                      @else
                        <span class="text-gray-400 italic">Sin comentarios</span>
                      @endif
                    </td>
                    <td class="text-left py-3 px-4">
                      {{-- show --}}
                      <a class="text-blue-400 hover:text-blue-600 mr-2" href="{{ route('admin.neighbors.show', $neighbor->uuid) }}">
                        <i class="fas fa-eye"></i>
                      </a>

                      {{-- edit --}}
                      <a class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-edit"></i>
                      </a>
                    </td>
                </tr>
              @endforeach
          </tbody>
      </table>
  </div>

  <div class="flex items-center gap-4 mt-5">
    @if ($page > 1 && $pages > 1)
      <a
        href="{{ route('admin.neighbors', [
          'page' => $page - 1,
      ]) }}"
      class="flex items-center gap-2 px-6 py-3 font-sans text-xs font-bold text-center text-gray-900 uppercase align-middle transition-all rounded-lg select-none bg-gray-200 hover:bg-gray-900/10 active:bg-gray-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
      >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
        aria-hidden="true" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 4.5L3 12m0 0l7.5 7.5M3 12h18"></path>
      </svg>
      Anterior
      </a>
      
    @endif
    <div class="flex items-center gap-2">
      <a
        href="{{ route('admin.neighbors', [
          'page' => 1,
      ]) }}"
      class="{{ $page == 1 ? 'relative h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg bg-gray-900 text-center align-middle font-sans text-xs font-medium uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none' : 'relative h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-gray-900 transition-all hover:bg-gray-900/10 active:bg-gray-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none' }} flex items-center justify-center"
      type="button">
        1
      </a>
      @for ($i = max($page - 3, 1); $i <= min($page + 3, $pages); $i++)
        @if($i != 1 && $i != $pages)
          <a
            href="{{ route('admin.neighbors', [
              'page' => $i,
          ]) }}"
          class="{{ $page == $i ? 'relative h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg bg-gray-900 text-center align-middle font-sans text-xs font-medium uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none' : 'relative h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-gray-900 transition-all hover:bg-gray-900/10 active:bg-gray-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none' }} flex items-center justify-center"
          type="button">
          {{ $i }}
          </a>
        @endif
      @endfor
        <a
        href="{{ route('admin.neighbors', [
          'page' => $pages,
      ]) }}"
      class="{{ $page == $pages ? 'relative h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg bg-gray-900 text-center align-middle font-sans text-xs font-medium uppercase text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none' : 'relative h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-gray-900 transition-all hover:bg-gray-900/10 active:bg-gray-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none' }} flex items-center justify-center"
      type="button">
      {{ $pages }}
      </a>
    </div>
    
    @if($page < $pages)
      <a
        href="{{ route('admin.neighbors', [
          'page' => $page + 1,
      ]) }}"
      class="flex items-center gap-2 px-6 py-3 font-sans text-xs font-bold text-center text-gray-900 uppercase align-middle transition-all rounded-lg select-none bg-gray-200 hover:bg-gray-900/10 active:bg-gray-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
      >
      Siguiente
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
        aria-hidden="true" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
      </svg>
      </a>
    @endif
  </div> 
</div>
@endsection