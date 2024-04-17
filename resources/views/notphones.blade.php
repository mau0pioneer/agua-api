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
            <th scope="col" colspan="1">#</th>
            <th scope="col" colspan="1">Nombre</th>
            <th scope="col" colspan="1">Dirección</th>
            <th scope="col" colspan="1">Periodos pendientes</th>
            <th scope="col" colspan="2">Teléfono</th>
            <th scope="col" colspan="4">Visitas</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($neighbors as $i => $neighbor)
            <tr>
              <td style="font-size: .8rem;" colspan="1">
                {{ $i + 1 }}
              </td>
              <td style="font-size: .8rem; text-transform: uppercase; width:200px" colspan="1">{{ $neighbor->firstname }} {{ $neighbor->lastname }}</td>
              <td style="font-size: .8rem; text-transform: uppercase; width:200px" colspan="1">
                {{ $neighbor->dwellings[0]->street->name }} {{ $neighbor->dwellings[0]->street_number }} {{ $neighbor->dwellings[0]->interior_number }}
              </td>
              <td style="font-size: .8rem;width:150px" colspan="1">
                @php
                  $dwelling = $neighbor->dwellings[0];
                  $pending_periods = $dwelling->periods->filter(function ($period) {
                    return $period->status === 'pending';
                  });
                  $pending_periods_text = $pending_periods->map(function ($period) {
                    return $period->getMonth() . ' ' . $period->year;
                  })->implode(', ');
                @endphp
                {{ $pending_periods_text }}.
              </td>
              <td colspan="2">
                <span style="opacity: 0;">
                  {{ "000-000-00-00" }}
                </span>
              </td>
              <td colspan="4">
                <div style="width: 300px; height:4rem; display:grid; grid-template-columns: 1fr 1fr 1fr 1fr; justify-content:end; margin: 0 0 0 auto;">
                  <div style="width:100%; height:100%; border: 1px solid gray;"></div>
                  <div style="width:100%; height:100%; border: 1px solid gray;"></div>
                  <div style="width:100%; height:100%; border: 1px solid gray;"></div>
                  <div style="width:100%; height:100%; border: 1px solid gray;"></div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>