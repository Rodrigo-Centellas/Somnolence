@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center pb-0">
      <h6 class="mb-0">Add Trip</h6>
      <a href="{{ route('trips.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Back
      </a>
    </div>
    <div class="card-body px-4 pt-4 pb-2">
      <form action="{{ route('trips.store') }}" method="POST">
        @csrf

<div class="mb-3">
  <label class="form-label">Usuarios disponibles</label>
  <div class="d-flex flex-wrap">
    @foreach($users as $u)
      @if ($u->estado !== 'ocupado')
        <div class="form-check me-4 mb-2" style="min-width: 200px;">
          <input
            class="form-check-input"
            type="checkbox"
            name="users[]"
            value="{{ $u->id }}"
            id="user-{{ $u->id }}"
            {{ in_array($u->id, old('users', $selected ?? [])) ? 'checked' : '' }}
          >
          <label class="form-check-label ms-1" for="user-{{ $u->id }}">
            {{ $u->nombre }}
          </label>
        </div>
      @endif
    @endforeach
  </div>
</div>



        <div class="mb-3">
          <label class="form-label">Ruta</label>
          <select name="route_id" class="form-control">
            @foreach($routes as $r)
              <option value="{{ $r->id }}">
                {{ $r->origen }} → {{ $r->destino }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Vehículo</label>
          <select name="vehicle_id" class="form-control">
            @foreach($vehicles as $v)
              <option value="{{ $v->id }}">{{ $v->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Distancia</label>
          <input type="number" name="distancia_recorrida" class="form-control" value="{{ old('distancia_recorrida') }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Estado</label>
          <select name="estado" class="form-control">
            <option value="1">Active</option>
            <option value="0">Completed</option>
          </select>
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Fecha Inicio</label>
          <input type="datetime-local" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}">
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Fecha Fin</label>
          <input type="datetime-local" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}">
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
