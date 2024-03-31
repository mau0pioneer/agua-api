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
    {{-- table --}}
    {{-- structure: {
      id: neighbor.id,
      name: neighbor.firstname + ' ' + neighbor.lastname,
      address: neighbor.dwellings[0].street.name + ' ' + neighbor.dwellings[0].street.number + ', ' + neighbor.dwellings[0].interior_number,
      phone: neighbor.phone_number,
    } --}}

    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">ID</th>
          <th scope="col">Nombre</th>
          <th scope="col">Dirección</th>
          <th scope="col">Teléfono</th>
          <th scope="col">Fecha y hora</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($neighbors as $i => $neighbor)
          <tr style="text-transform: uppercase;">
            <th>
              {{ $i+1 }}
            </th>
            <th scope="row">{{ $neighbor->id }}</th>
            <td>{{ $neighbor->firstname . ' ' . $neighbor->lastname }}</td>
            <td>
              @foreach($neighbor->contributions as $contribution)
                {{ $contribution->dwelling->street->name . ' ' . $contribution->dwelling->street_number . ' ' . $contribution->dwelling->interior_number}}
              @endforeach
            </td>
            <td>{{ $neighbor->phone_number }}</td>
            <td></td>
          </tr>
        @endforeach
      </tbody>
  </div>
</body>
</html>