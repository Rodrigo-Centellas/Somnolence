@extends('layouts.app')

@section('content')
<div class="col-lg-10 col-12 mt-4 mt-lg-0">
  <div class="card mb-4">
    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Gestión de Rutas</h6>
      <a href="{{ route('rutas.create') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-plus me-1"></i> Nueva Ruta
      </a>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
      <div class="table-responsive p-0">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Lat. Origen</th>
              <th>Lng. Origen</th>
              <th>Lat. Destino</th>
              <th>Lng. Destino</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rutas as $ruta)
            <tr>
              <td>{{ $ruta->nombre }}</td>
              <td>{{ $ruta->latitud_origen }}</td>
              <td>{{ $ruta->longitud_origen }}</td>
              <td>{{ $ruta->latitud_destino }}</td>
              <td>{{ $ruta->longitud_destino }}</td>
              <td class="text-end">
                <a href="{{ route('rutas.edit', $ruta) }}" class="btn btn-sm btn-warning me-2">Editar</a>
                <form action="{{ route('rutas.destroy', $ruta) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta ruta?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
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
