@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Routes Management</h6>
      <a href="{{ route('rutas.create') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-plus me-1"></i> Add Route
      </a>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
      <div class="table-responsive p-0">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Origin</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Destination</th>
              <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Distance</th>
              <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
              <th class="text-secondary opacity-7"></th>
            </tr>
          </thead>
          <tbody>
            @foreach($rutas as $ruta)
            <tr>
              <td>
                <p class="text-xs font-weight-bold mb-0">{{ $ruta->origen }}</p>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">{{ $ruta->destino }}</p>
              </td>
              <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">{{ $ruta->distancia }}</span>
              </td>
              <td class="align-middle text-center">
 <span class="text-secondary text-xs font-weight-bold">{{ $ruta->nombre }}</span>
              </td>
              <td class="align-middle text-end">
                <a href="{{ route('rutas.edit', $ruta) }}"
                   class="text-secondary font-weight-bold text-xs me-2">
                  Edit
                </a>
                <form action="{{ route('rutas.destroy', $ruta) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('¿Eliminar esta ruta?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="btn btn-link text-danger p-0 m-0 text-xs align-baseline">
                    <i class="fa fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // … tu inicialización de gráficos, si la necesitas …
</script>
@endpush
