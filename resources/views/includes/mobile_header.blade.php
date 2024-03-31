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

<header x-data="{ isOpen: false }" class="w-full bg-sidebar py-5 px-6 sm:hidden">
  <div class="flex items-center justify-between">
      <a href="{{ route('admin.index') }}" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
      <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">
          <i x-show="!isOpen" class="fas fa-bars"></i>
          <i x-show="isOpen" class="fas fa-times"></i>
      </button>
  </div>

  <!-- Dropdown Nav -->
  <nav :class="isOpen ? 'flex': 'hidden'" class="flex flex-col pt-4">
        @foreach ($links as $link)
            <a href="{{ route($link['route']) }}" class="flex items-center text-white py-2 pl-4 nav-item {{ Route::is($link['route']) ? 'active-nav-link' : '' }}">
                <i class="{{ $link['icon'] }} mr-3"></i>
                {{ $link['name'] }}
            </a>
        @endforeach
      <a href="#" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
          <i class="fas fa-sign-out-alt mr-3"></i>
          Sign Out
      </a>
      <button class="w-full bg-white cta-btn font-semibold py-2 mt-3 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
          <i class="fas fa-arrow-circle-down mr-3"></i> Cerrar sesión
      </button>
  </nav>
  <!-- <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
      <i class="fas fa-plus mr-3"></i> New Report
  </button> -->
</header>