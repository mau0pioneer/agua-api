@php
    $links = [
        [
            "name" => "Dashboard",
            "route" => "admin.index",
            "icon" => "fas fa-tachometer-alt"
        ],
        [
            "name" => "Recibos",
            "route" => "admin.contributions",
            "icon" => "fas fa-sticky-note"
        ],
        [
            "name" => "Vecinos",
            "route" => "admin.neighbors",
            "icon" => "fas fa-users"
        ],
        [
            "name" => "Direcciones",
            "route" => "admin.dwellings",
            "icon" => "fas fa-tablet-alt"
        ]
    ];
@endphp

<aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
  <div class="p-6">
      <a href="{{ route('admin.index') }}" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Agua</a>
      <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
          <i class="fas fa-plus mr-3"></i> Registrar recibo
      </button>
  </div>
  <nav class="text-white text-base font-semibold pt-3">
        @foreach ($links as $link)
            <a href="{{ route($link['route']) }}" class="flex items-center text-white py-4 pl-6 nav-item {{ Route::is($link['route']) ? 'active-nav-link' : '' }}">
                <i class="{{ $link['icon'] }} mr-3"></i>
                {{ $link['name'] }}
            </a>
        @endforeach
  </nav>
  <a href="#" class="absolute w-full upgrade-btn bottom-0 active-nav-link text-white flex items-center justify-center py-4">
      <i class="fas fa-arrow-circle-down mr-3"></i>
      Cerrar sesión
  </a>
</aside>