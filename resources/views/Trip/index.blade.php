@extends('layouts.app')

@section('content')
<div class="col-lg-10 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center pb-0">
      <h6 class="mb-0">Gestión de Viajes</h6>
      <a href="{{ route('trips.create') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-plus me-1"></i> Añadir Viaje
      </a>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
      <div class="table-responsive p-0">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th>Usuarios</th>
              <th>Ruta</th>
              <th>Vehículo</th>
              <th class="text-center">Estado</th>
              <th class="text-center">Inicio</th>
              <th class="text-center">Hora</th>
              <th class="text-center">Fin</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($trips as $trip)
            <tr>
              <td>{{ $trip->users->pluck('nombre')->join(', ') }}</td>
              <td>{{ $trip->route->nombre }}</td>
              <td>{{ $trip->vehicle->nombre }}</td>
              <td class="text-center">
                <span class="badge bg-{{ $trip->estado === 'activo' ? 'success' : 'secondary' }}">
                  {{ ucfirst($trip->estado) }}
                </span>
              </td>
              <td class="text-center">{{ \Carbon\Carbon::parse($trip->fecha_inicio)->format('d/m/Y H:i') }}</td>
              <td class="text-center">{{ \Carbon\Carbon::parse($trip->hora_inicio)->format('H:i') }}</td>
              <td class="text-center">
                {{ $trip->fecha_fin ? \Carbon\Carbon::parse($trip->fecha_fin)->format('d/m/Y H:i') : '—' }}
              </td>
              <td class="text-end">
                <a href="{{ route('trips.edit', $trip) }}" class="btn btn-sm btn-warning me-2">Editar</a>
                <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este viaje?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
