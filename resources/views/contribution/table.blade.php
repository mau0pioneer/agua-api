<table class="min-w-full bg-white">
  <thead class="bg-gray-800 text-white">
      <tr>
          <th class="text-left py-3 px-4 uppercase font-semibold text-sm">FOLIO</th>
          <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Fecha</th>
          <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Cantidad</th>
          <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Emitido por</th>
          @if (!isset($hidden_neighbor))
            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Vecino</th>
          @endif
          @if (isset($view_comments) && $view_comments)
            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Comentarios</th>
          @endif
          <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Acciones</th>
      </tr>
  </thead>
  <tbody class="text-gray-700">
      @foreach ($contributions as $contribution)
        @php
          $color = 'bg-yellow-50';
          if ($contribution->collector_uuid && $contribution->amount) {
            $color = 'bg-green-50';
          } else if ($contribution->status === 'canceled') {
            $color = 'bg-red-50';
          }
        @endphp
        <tr class="hover:bg-gray-100 {{ $color }}">
            <td class="text-left py-3 px-4">{{ $contribution->folio }}</td>
            <td class="text-left py-3 px-4 capitalize">
              @if($contribution->status === 'finalized')
                {{ $contribution->created_at->isoFormat('D / MMMM / YYYY') }}
              @endif

            </td>
            <td class="text-left py-3 px-4">
              ${{ number_format($contribution->amount, 2) }}
            </td>
            <td class="text-left py-3 px-4">{{ $contribution->collector->name ?? '' }}</td>
            @if (!isset($hidden_neighbor))
            <td class="text-left py-3 px-4">
              @if ($contribution->neighbor)
                <a
                  href="{{ route('admin.neighbors.show', $contribution->neighbor_uuid) }}"
                  class="text-blue-400 hover:text-blue-600 capitalize"
                >
                  {{ $contribution->neighbor->firstname }} {{ $contribution->neighbor->lastname }}
                </a>
              @endif
            </td>
            @endif
            @if (isset($view_comments) && $view_comments)
              <td class="text-left py-3 px-4">
                @if ($contribution->comments)
                  {{ $contribution->comments }}
                @else
                  <span class="text-gray-400 italic">Sin comentarios</span>
                @endif
              </td>
            @endif
            <td class="text-left py-3 px-4">
              {{-- show --}}
              <a class="text-blue-400 hover:text-blue-600 mr-2" href="{{ route('admin.contributions.show', $contribution->uuid) }}">
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