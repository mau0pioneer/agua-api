@php
  $typeNames = [
    '1' => 'Un nivel',
    '2' => 'Dos niveles',
    '3' => 'Departamento cuadruplex',
    '4' => 'Departamento Sextuplex',
  ];
@endphp

<table class="min-w-full bg-white">
  <thead class="bg-gray-800 text-white">
      <tr>
          <th class="text-left py-3 px-4 uppercase font-semibold text-sm">ID</th>
          <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Dirección</th>
          <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Estado</th>
          <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tipo</th>
          <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Acciones</th>
      </tr>
  </thead>
  <tbody class="text-gray-700">
      @foreach ($dwellings as $dwelling)
        <tr class="hover:bg-gray-100">
            <td class="text-left py-3 px-4">{{ $dwelling->id }}</td>
            <td class="text-left py-3 px-4 capitalize">
              {{ $dwelling->street->name ?? '' }} {{ $dwelling->street_number }} {{ $dwelling->interior_number }}
            </td>
            <td class="text-left py-3 px-4">
              <span class="inline-block w-2 h-2 rounded-full {{ $dwelling->inhabited ? 'bg-blue-500' : 'bg-gray-300' }}"></span>
              {{ $dwelling->inhabited ? 'Habitada' : 'Deshabitada' }}
            </td>
            <td class="text-left py-3 px-4">
              <span class="inline-block w-2 h-2 rounded-full" style="background-color: {{ $dwelling->type_color }}"></span>
              {{ $typeNames[$dwelling->type] }}
            </td>
            <td class="text-left py-3 px-4">
              {{-- show --}}
              <a class="text-blue-400 hover:text-blue-600 mr-2" href="{{ route('admin.dwellings.show', $dwelling->uuid) }}">
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