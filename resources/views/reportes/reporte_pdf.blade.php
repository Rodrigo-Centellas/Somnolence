<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Viajes</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #aaa; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Reporte de Viajes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha Inicio</th>
                <th>Estado</th>
                <th>Distancia (km)</th>
                <th>Vehículo</th>
                <th>Usuarios</th>
            </tr>
        </thead>
        <tbody>
            @foreach($viajes as $viaje)
            <tr>
                <td>{{ $viaje->id }}</td>
                <td>{{ $viaje->fecha_inicio }}</td>
                <td>{{ ucfirst($viaje->estado) }}</td>
                <td>{{ $viaje->distancia_recorrida }}</td>
                <td>{{ optional($viaje->vehicle)->placa }}</td>
                <td>{{ $viaje->users->pluck('nombre')->join(', ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
