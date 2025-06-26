@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="card">
    <div class="card-header">
      <h5>Generar Reporte de Viajes</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('reportes.viajes.index') }}" method="GET" class="row g-3">
        @csrf

        <div class="col-md-3">
          <label for="fecha_inicio" class="form-label">Fecha desde</label>
          <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
        </div>

        <div class="col-md-3">
          <label for="fecha_fin" class="form-label">Fecha hasta</label>
          <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
        </div>

        <div class="col-md-3">
          <label for="estado" class="form-label">Estado</label>
          <select name="estado" class="form-select">
            <option value="">Todos</option>
            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="en curso" {{ request('estado') == 'en curso' ? 'selected' : '' }}>En curso</option>
            <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
          </select>
        </div>

        <div class="col-md-3">
          <label for="vehiculo_id" class="form-label">Vehículo</label>
          <select name="vehiculo_id" class="form-select">
            <option value="">Todos</option>
            @foreach($vehiculos as $vehiculo)
              <option value="{{ $vehiculo->id }}" {{ request('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                {{ $vehiculo->placa }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label for="user_id" class="form-label">Usuario</label>
          <select name="user_id" class="form-select">
            <option value="">Todos</option>
            @foreach($usuarios as $usuario)
              <option value="{{ $usuario->id }}" {{ request('user_id') == $usuario->id ? 'selected' : '' }}>
                {{ $usuario->nombre }} {{ $usuario->apellido }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-filter"></i> Filtrar
          </button>
        </div>

        <div class="col-md-3 d-flex align-items-end">
          <a href="{{ route('reportes.viajes.excel', request()->query()) }}" class="btn btn-success w-100">
            <i class="fas fa-file-excel"></i> Exportar Excel
          </a>
        </div>

        <div class="col-md-3 d-flex align-items-end">
          <a href="{{ route('reportes.viajes.pdf', request()->query()) }}" target="_blank" class="btn btn-danger w-100">
            <i class="fas fa-file-pdf"></i> Exportar PDF
          </a>
        </div>
      </form>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h6>Previsualización de resultados</h6>
    </div>
    <div class="card-body table-responsive">
      @if(count($viajes) > 0)
      <table class="table table-bordered table-hover">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Ruta</th>
            <th>Vehículo</th>
            <th>Usuario(s)</th>
            <th>Fecha Inicio</th>
            <th>Distancia</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          @foreach($viajes as $viaje)
          <tr>
            <td>{{ $viaje->id }}</td>
            <td>{{ $viaje->route->nombre ?? 'N/A' }}</td>
            <td>{{ $viaje->vehicle->placa ?? 'N/A' }}</td>
            <td>
              @foreach ($viaje->users as $usuario)
                {{ $usuario->nombre }} {{ $usuario->apellido }}<br>
              @endforeach
            </td>
            <td>{{ $viaje->fecha_inicio }}</td>
            <td>{{ $viaje->distancia_recorrida }} km</td>
            <td>{{ ucfirst($viaje->estado) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
        <p class="text-center text-muted">No hay resultados para mostrar.</p>
      @endif
    </div>
  </div>
</div>
@endsection
