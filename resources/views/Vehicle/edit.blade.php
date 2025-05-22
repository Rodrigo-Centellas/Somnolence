@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-12 mt-4 mt-lg-0">
  <div class="container-fluid py-4">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-10">
        <div class="card mb-4">
          <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Editar Vehículo</h6>
            <a href="{{ route('vehicle_index') }}" class="btn btn-sm btn-secondary">
              <i class="fa fa-arrow-left me-1"></i> Volver
            </a>
          </div>
          <div class="card-body px-4 pt-4 pb-2">
            <form action="{{ route('vehicles.update', $vehicle) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="input-group input-group-outline mb-3">
                <label class="form-label ps-2 pt-2 pb-2 px-2">Modelo</label>
                <input type="text" name="modelo" class="form-control"
                       placeholder="{{ $vehicle->modelo }}"
                       value="{{ old('modelo', $vehicle->modelo) }}">
              </div>

              <div class="input-group input-group-outline mb-3">
                <label class="form-label ps-2 pt-2 pb-2 px-2">Nombre</label>
                <input type="text" name="nombre" class="form-control"
                       placeholder="{{ $vehicle->nombre }}"
                       value="{{ old('nombre', $vehicle->nombre) }}">
              </div>

              <div class="input-group input-group-outline mb-3">
                <label class="form-label ps-2 pt-2 pb-2 px-2">Placa</label>
                <input type="text" name="placa" class="form-control"
                       placeholder="{{ $vehicle->placa }}"
                       value="{{ old('placa', $vehicle->placa) }}">
              </div>

              <div class="input-group input-group-outline mb-3">
                <label class="form-label ps-2 pt-2 pb-2 px-2">Capacidad</label>
                <input type="number" name="capacidad" class="form-control"
                       placeholder="{{ $vehicle->capacidad }}"
                       value="{{ old('capacidad', $vehicle->capacidad) }}">
              </div>

              <div class="input-group input-group-outline mb-3">
                <label class="form-label ps-2 pt-2 pb-2 px-2">Velocidad Máxima</label>
                <input type="number" name="velocidad_maxima" class="form-control"
                       placeholder="{{ $vehicle->velocidad_maxima }}"
                       value="{{ old('velocidad_maxima', $vehicle->velocidad_maxima) }}">
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
    </div>
  </div>
</div>
@endsection
