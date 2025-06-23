@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="card">
    <div class="card-header">
      <h5>Listado de Eventos</h5>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('eventos.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
          <label for="fecha" class="form-label">Fecha</label>
          <input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control">
        </div>

        <div class="col-md-3">
          <label for="tipo" class="form-label">Tipo de evento</label>
          <select name="tipo" class="form-select">
            <option value="">Todos</option>
            @foreach($tipos as $tipo)
              <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>
                {{ $tipo }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label for="nivel" class="form-label">Nivel de severidad</label>
          <select name="nivel" class="form-select">
            <option value="">Todos</option>
            @foreach($niveles as $nivel)
              <option value="{{ $nivel }}" {{ request('nivel') == $nivel ? 'selected' : '' }}>
                {{ $nivel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label for="usuario" class="form-label">Buscar por usuario</label>
          <input type="text" name="usuario" value="{{ request('usuario') }}" class="form-control" placeholder="Nombre o apellido">
        </div>

        <div class="col-md-12 text-end">
          <button class="btn btn-primary">
            <i class="fas fa-filter"></i> Filtrar
          </button>
        </div>
      </form>

      @if($eventos->isEmpty())
        <div class="alert alert-info">No se encontraron eventos con los filtros aplicados.</div>
      @else
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Tipo</th>
                <th>Nivel</th>
                <th>Mensaje</th>
                <th>Usuario</th>
                <th>Vehículo</th>
                <th>Ruta</th>
              </tr>
            </thead>
            <tbody>
              @foreach($eventos as $evento)
                <tr>
                  <td>{{ $evento->fecha }}</td>
                  <td>{{ $evento->hora }}</td>
                  <td>{{ $evento->tipo }}</td>
                  <td>{{ $evento->nivel }}</td>
                  <td>{{ $evento->mensaje }}</td>
                  <td>
                    @if($evento->trip && $evento->trip->users->isNotEmpty())
                      {{ $evento->trip->users->first()->nombre ?? '' }} {{ $evento->trip->users->first()->apellido ?? '' }}
                    @else
                      -
                    @endif
                  </td>
                  <td>{{ $evento->trip->vehicle->placa ?? '-' }}</td>
                  <td>{{ $evento->trip->route->nombre ?? '' }} - {{ $evento->trip->route->destino ?? '' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
