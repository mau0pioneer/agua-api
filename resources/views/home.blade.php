<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row">
            @foreach($folios as $index => $folio)
                @php
                    $color = 'warning';
                    if($folio['CANCELADO']) $color = 'danger';
                    if($folio['FECHA'] && $folio['CANTIDAD']) $color = 'success';
                @endphp
                @if($index % 23 == 0)
                    <div class="col-md-4">
                        <ul class="list-group">
                @endif
                            <li class="list-group-item p-1 m-0 fs-7 list-group-item-{{ $color }}" style="font-size: .8rem">
                                <strong>FOLIO:</strong> {{ $folio['FOLIO'] }}<br>
                                <strong>EMITIDO POR:</strong> {{ $folio['COLECTOR'] }}<br>
                                <strong>CANTIDAD:</strong> {{ $folio['CANTIDAD'] }}<br>
                            </li>
                @if(($index + 1) % 23 == 0 || $index == count($folios) - 1)
                        </ul>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</body>
</html>
