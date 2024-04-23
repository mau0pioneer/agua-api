<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Dirección</th>
          <th scope="col">Periodos faltantes</th>
          <th scope="col">Nombre</th>
          <th scope="col">Teléfono</th>
          <th scope="col">Fecha primer visita</th>
          <th scope="col">Fecha agendada</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($dwellings as $dwellingss)
        @foreach ($dwellingss as $i=>$dwelling)
        <tr>
          <th scope="row" >{{ $i+1 }}</th>
          <th style="text-transform: uppercase;">
            {{ $dwelling->street->name }} {{ $dwelling->street_number }} {{ $dwelling->interior_number }}
          </th>
          <td>
            <ul>
              @foreach ($dwelling->pendingPeriodss as $period)
                <li>
                  {{ $period->year }}-{{ $period->getMonth() }}
                </li> 
              @endforeach
            </ul>
            <div class="opacity-0">
              mark anthony
            </div>
          </td>
          <td>
            <ul>
              @foreach ($dwelling->neighbors as $neighbor)
                <li>
                  {{ $neighbor->firstname }} {{ $neighbor->lastname }}  
                </li> 
              @endforeach
            </ul>
            <div class="opacity-0">
              mark anthony
            </div>
          </td>
          <td>
            <ul>
              @foreach ($dwelling->neighbors as $neighbor)
                <li>
                  {{ $neighbor->phone_number }}
                </li> 
              @endforeach
            </ul>
            <div class="opacity-0">
              mark anthony
            </div>
          </td>
          <td class="opacity-0">
            miercoles 13 de octubre de 2021
          </td>
          <td class="opacity-0">
            miercoles 13 de octubre de 2021
          </td>
        </tr>
        @endforeach
        @endforeach
        
      </tbody>
    </table>
  </body>
</html>