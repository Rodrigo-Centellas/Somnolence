@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center pb-0">
      <h6 class="mb-0">Nuevo Viaje</h6>
      <a href="{{ route('trips.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Volver
      </a>
    </div>
    <div class="card-body px-4 pt-4 pb-2">
      <form action="{{ route('trips.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label">Usuarios disponibles</label>
          <div class="d-flex flex-wrap">
            @foreach($users as $u)
              <div class="form-check me-4 mb-2" style="min-width: 200px;">
                <input class="form-check-input" type="checkbox" name="users[]" value="{{ $u->id }}" id="user-{{ $u->id }}">
                <label class="form-check-label ms-1" for="user-{{ $u->id }}">
                  {{ $u->nombre }}
                </label>
              </div>
            @endforeach
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Ruta</label>
          <select name="route_id" class="form-control" required>
            @foreach($routes as $r)
              <option value="{{ $r->id }}">{{ $r->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Vehículo</label>
          <select name="vehicle_id" class="form-control" required>
            @foreach($vehicles as $v)
              <option value="{{ $v->id }}">{{ $v->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Estado</label>
          <select name="estado" class="form-control">
            <option value="activo">Activo</option>
            <option value="completado">Completado</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Fecha Inicio</label>
          <input type="datetime-local" name="fecha_inicio" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Fecha Fin (opcional)</label>
          <input type="datetime-local" name="fecha_fin" class="form-control">
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
