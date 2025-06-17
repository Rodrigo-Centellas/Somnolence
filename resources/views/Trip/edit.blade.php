@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center pb-0">
      <h6 class="mb-0">Editar Viaje</h6>
      <a href="{{ route('trips.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Volver
      </a>
    </div>
    <div class="card-body px-4 pt-4 pb-2">
      <form action="{{ route('trips.update', $trip) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
          <label class="form-label">Usuarios</label>
          <select name="users[]" class="form-control" multiple>
            @foreach($users as $u)
              <option value="{{ $u->id }}" {{ in_array($u->id, $selected) ? 'selected':'' }}>
                {{ $u->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Ruta</label>
          <select name="route_id" class="form-control" required>
            @foreach($routes as $r)
              <option value="{{ $r->id }}" {{ $r->id == $trip->route_id ? 'selected':'' }}>
                {{ $r->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Vehículo</label>
          <select name="vehicle_id" class="form-control" required>
            @foreach($vehicles as $v)
              <option value="{{ $v->id }}" {{ $v->id == $trip->vehicle_id ? 'selected':'' }}>
                {{ $v->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Estado</label>
          <select name="estado" class="form-control">
            <option value="activo" {{ $trip->estado === 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="completado" {{ $trip->estado === 'completado' ? 'selected' : '' }}>Completado</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Fecha Inicio</label>
          <input type="datetime-local" name="fecha_inicio" class="form-control"
                 value="{{ \Carbon\Carbon::parse($trip->fecha_inicio)->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Fecha Fin (opcional)</label>
          <input type="datetime-local" name="fecha_fin" class="form-control"
                 value="{{ optional($trip->fecha_fin) ? \Carbon\Carbon::parse($trip->fecha_fin)->format('Y-m-d\TH:i') : '' }}">
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
