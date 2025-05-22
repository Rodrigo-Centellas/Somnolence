@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Add Route</h6>
      <a href="{{ route('rutas.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Back
      </a>
    </div>
    <div class="card-body px-4 pt-4 pb-2">
      <form action="{{ route('rutas.store') }}" method="POST">
        @csrf

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" value="{{ old('Nombre') }}">
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Origin</label>
          <input type="text" name="origen" class="form-control" value="{{ old('origen') }}">
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Destination</label>
          <input type="text" name="destino" class="form-control" value="{{ old('destino') }}">
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Distance</label>
          <input type="number" name="distancia" class="form-control" value="{{ old('distancia') }}">
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Status</label>
          <select name="estado" class="form-control">
            <option value="1" {{ old('estado')=='1' ? 'selected':'' }}>Active</option>
            <option value="0" {{ old('estado')=='0' ? 'selected':'' }}>Inactive</option>
          </select>
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

@push('scripts')
<script>
  // … si necesitas gráficas …
</script>
@endpush
