@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Edit Route</h6>
      <a href="{{ route('rutas.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Back
      </a>
    </div>
    <div class="card-body px-4 pt-4 pb-2">
      <form action="{{ route('rutas.update', $ruta) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Origin</label>
          <input type="text" name="origen" class="form-control"
                 value="{{ old('origen', $ruta->origen) }}">
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Destination</label>
          <input type="text" name="destino" class="form-control"
                 value="{{ old('destino', $ruta->destino) }}">
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Distance</label>
          <input type="number" name="distancia" class="form-control"
                 value="{{ old('distancia', $ruta->distancia) }}">
        </div>

        <div class="input-group input-group-outline mb-3">
          <label class="form-label">Status</label>
          <select name="estado" class="form-control">
            <option value="1" {{ $ruta->estado ? 'selected':'' }}>Active</option>
            <option value="0" {{ !$ruta->estado ? 'selected':'' }}>Inactive</option>
          </select>
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

@push('scripts')
<script>
  // … tus scripts aquí …
</script>
@endpush
