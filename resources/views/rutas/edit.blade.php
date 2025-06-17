@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center pb-0">
      <h6 class="mb-0">Editar Ruta</h6>
      <a href="{{ route('rutas.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Volver
      </a>
    </div>
    <div class="card-body px-4 pt-4 pb-2">
      <form action="{{ route('rutas.update', $ruta) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $ruta->nombre) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Latitud Origen</label>
          <input type="number" step="any" name="latitud_origen" class="form-control" value="{{ old('latitud_origen', $ruta->latitud_origen) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Longitud Origen</label>
          <input type="number" step="any" name="longitud_origen" class="form-control" value="{{ old('longitud_origen', $ruta->longitud_origen) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Latitud Destino</label>
          <input type="number" step="any" name="latitud_destino" class="form-control" value="{{ old('latitud_destino', $ruta->latitud_destino) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Longitud Destino</label>
          <input type="number" step="any" name="longitud_destino" class="form-control" value="{{ old('longitud_destino', $ruta->longitud_destino) }}" required>
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
