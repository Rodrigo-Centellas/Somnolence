@extends('layouts.app')

@section('content')
<div class="col-lg-10 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center pb-0">
      <h6 class="mb-0">Gestión de Paradas</h6>
      <a href="{{ route('stops.create') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-plus me-1"></i> Nueva Parada
      </a>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
      <div class="table-responsive p-0">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th>Nombre</th>
              <th class="text-center">Latitud</th>
              <th class="text-center">Longitud</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($stops as $stop)
            <tr>
              <td>{{ $stop->nombre }}</td>
              <td class="text-center">{{ number_format($stop->latitud, 6) }}</td>
              <td class="text-center">{{ number_format($stop->longitud, 6) }}</td>
              <td class="text-end">
                <a href="{{ route('stops.edit', $stop) }}" class="btn btn-sm btn-warning me-2">
                  Edit
                </a>
                <a href="#"
                   class="btn btn-sm btn-danger"
                   onclick="event.preventDefault();
                            if(confirm('¿Eliminar esta parada?')) {
                              document.getElementById('delete-form-{{ $stop->id }}').submit();
                            }">
                  Delete
                </a>
                <form id="delete-form-{{ $stop->id }}"
                      action="{{ route('stops.destroy', $stop) }}"
                      method="POST" class="d-none">
                  @csrf
                  @method('DELETE')
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
