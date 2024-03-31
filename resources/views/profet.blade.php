{{-- html 5 and bootstrap cdn --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  {{-- bootstrap cdn --}}
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <div class="container pt-2">
    @foreach ($streetsUuids as $streetUuid => $dwellings)
      {{-- card --}}
      <div class="card mb-5">
        <div class="card-header">
          <h3 style="text-transform: uppercase;">{{ $dwellings[0]->street->name }}</h3>
        </div>

        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Dirección</th>
                <th>Vecino</th>
                <th>Teléfono</th>
                <th>Completo</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($dwellings as $dwelling)
                <tr class="text-uppercase">
                  <td>{{ $dwelling->street->name }} {{ $dwelling->street_number }} {{ $dwelling->interior_number }}</td>
                  <td>
                    <ul>
                      @foreach ($dwelling->neighbors as $neighbor)
                        <li class="list-unstyled">{{ $neighbor->id }} - {{ $neighbor->firstname }} {{ $neighbor->lastname }}</li>
                      @endforeach
                    </ul>
                  </td>
                  <td>
                    <ul>
                      @foreach ($dwelling->neighbors as $neighbor)
                        <li class="list-unstyled">{{ $neighbor->phone_number }}</li>
                      @endforeach
                    </ul>
                  </td>
                  <td></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endforeach
  </div>
</body>
</html>