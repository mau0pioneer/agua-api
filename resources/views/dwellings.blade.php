@php
  $months = [
    '01' => 'Enero',
    '02' => 'Febrero',
    '03' => 'Marzo',
    '04' => 'Abril',
    '05' => 'Mayo',
    '06' => 'Junio',
    '07' => 'Julio',
    '08' => 'Agosto',
    '09' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre',
  ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  {{-- dwellings array - to list --}}
  {{-- 
    
    'uuid',
        'coordinates_uuid',
        'street_uuid',
        'street_number',
        'interior_number',
        'inhabited',
        'type',
        'comments'
    --}}
  @foreach ($dwellings as $street_dwellings)
  <h1
    style="
      text-align: center;
      margin: 2rem 0;
      text-transform: uppercase;
    "
  >
    Calle: {{ $street_dwellings[0]->street->name }}
  </h1>
  <ul>
    @foreach ($street_dwellings as $dwelling)
      @php
        $total = 0;
      @endphp
      {{-- style card --}}
      <li
        style="
          border: 1px solid #000;
          padding: 10px;
          margin: 10px;
          list-style: none;
        "
      >
        <p>Dirección: {{ $dwelling->street->name }} {{ $dwelling->street_number }} {{ $dwelling->interior_number }}</p>
        {{-- periods pending --}}
        <p>
          Periodos: 
          <div style="display: grid; gap: 1rem;">
            @foreach ($dwelling->periods as $period)
            @if ($period->status === 'pending')
            @php
              $total += $period->amount;
            @endphp
            <div>
              <div>
                {{ $months[$period->month] }} - {{ $period->year }}
              </div>
              <div>
                Costo: ${{ number_format($period->amount, 2) }}
              </div>
            </div>
            @endif
          @endforeach
          </div>
        </p>
        <p>
          Total pendiente: ${{ number_format($total, 2) }}
        </p>
      </li>
    @endforeach
  </ul>
  @endforeach
</body>
</html>