@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center pb-0">
      <h6 class="mb-0">Edit Trip</h6>
      <a href="{{ route('trips.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Back
      </a>
    </div>
    <div class="card-body px-4 pt-4 pb-2">
      <form action="{{ route('trips.update', $trip) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
          <label class="form-label">Usuarios</label>
          <select name="users[]" class="form-control" multiple>
            @foreach($users as $u)
              <option value="{{ $u->id }}"
                {{ in_array($u->id, $selected) ? 'selected':'' }}>
                {{ $u->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Ruta</label>
          <select name="ruta_id" class="form-control">
            @foreach($routes as $r)
              <option value="{{ $r->id }}"
                {{ $r->id == $trip->ruta_id ? 'selected':'' }}>
                {{ $r->origen }} → {{ $r->destino }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Vehículo</label>
          <select name="vehicle_id" class="form-control">
            @foreach($vehicles as $v)
              <option value="{{ $v->id }}"
                {{ $v->id == $trip->vehicle_id ? 'selected':'' }}>
                {{ $v->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Distancia</label>
          <input type="number" name="distancia_recorrida" class="form-control"
                 value="{{ old('distancia_recorrida', $trip->distancia_recorrida) }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Estado</label>
          <select name="estado" class="form-control">
            <option value="1" {{ $trip->estado ? 'selected':'' }}>Active</option>
            <option value="0" {{ !$trip->estado ? 'selected':'' }}>Completed</option>
          </select>
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Fecha Inicio</label>
          <input type="datetime-local" name="fecha_inicio" class="form-control"
                 value="{{ \Carbon\Carbon::parse($trip->fecha_inicio)->format('Y-m-d\TH:i') }}">
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Fecha Fin</label>
          <input type="datetime-local" name="fecha_fin" class="form-control"
                 value="{{ optional($trip->fecha_fin)
                    ? \Carbon\Carbon::parse($trip->fecha_fin)->format('Y-m-d\TH:i')
                    : '' }}">
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Update
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
