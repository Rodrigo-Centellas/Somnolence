@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Vehículos</h6>
      <a href="{{ route('vehicles.create') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-plus me-1"></i> Añadir Vehículo
      </a>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
      <div class="table-responsive p-0">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Modelo</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nombre</th>
              <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Placa</th>
              <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Capacidad</th>
              <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vel. Máx.</th>
              <th class="text-secondary opacity-7"></th>
            </tr>
          </thead>
          <tbody>
            @foreach($vehicles as $v)
            <tr>
              <td>
                <p class="text-xs font-weight-bold mb-0 ps-4">{{ $v->modelo }}</p>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">{{ $v->nombre }}</p>
              </td>
              <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">{{ $v->placa }}</span>
              </td>
              <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">{{ $v->capacidad }}</span>
              </td>
              <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">{{ $v->velocidad_maxima }}</span>
              </td>
              <td class="align-middle text-end">
                <a href="{{ route('vehicles.edit', $v) }}" class="text-secondary font-weight-bold text-xs me-2">
                  Edit
                </a>
                <form action="{{ route('vehicles.destroy', $v) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('¿Seguro que quieres eliminar este vehículo?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-link text-danger p-0 m-0 text-xs align-baseline  ps-2 pt-2 pb-2 px-2">
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
