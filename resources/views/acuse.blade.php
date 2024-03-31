<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col" colspan="1">NOMBRE</th>
            <th scope="col">DIRECCIÓN</th>
            <th scope="col">TELÉFONO</th>
            <th scope="col" colspan="3">FIRMA</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($dwellings as $i => $dwelling)
          @php
            // saber si es par o impar
            $color = $i % 2 === 0 ? 'white' : '#c6ffbd';
          @endphp
          <tr style="text-transform: uppercase;">
            <td style="width: 300px; background-color: {{ $color }};">
              <ul>
                @foreach ($dwelling->neighbors as $neighbor)
              <li>
                {{ $neighbor->firstname }} {{ $neighbor->lastname }}
              </li>
            @endforeach
              </ul>
            </td>
            
            <td style="width: 300px; background-color: {{ $color }};">
              {{ $dwelling->street->name ?? '' }} {{ $dwelling->street_number ?? '' }} {{ $dwelling->interior_number ?? '' }}
            </td>
            <td style="width: 300px; background-color: {{ $color }};">
              {{ $dwelling->neighbors[0]->phone ?? '' }}
            </td>
            <td colspan="3" style="width: 500px; background-color: {{ $color }};"></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>