@extends('layouts.app')

@section('content')
  <div class="col-lg-6 col-12 mt-4 mt-lg-0">
    <div class="container-fluid py-4">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-10">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Edit User</h6>
              <a href="{{ route('user_index') }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Back
              </a>
            </div>
            <div class="card-body ps-5 px-4 pt-4 pb-2">
              <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="input-group input-group-outline mb-3">
                  <label class="form-label ps-2 pt-2 pb-2 px-2">Nombre</label>
                  <input type="text" name="nombre" 
                         class="form-control ps-3" 
                         value="{{ old('nombre', $user->nombre) }}">
                </div>

                <div class="input-group input-group-outline mb-3">
                  <label class="form-label ps-2 pt-2 pb-2 px-2">Apellido</label>
                  <input type="text" name="apellido" 
                         class="form-control ps-3" 
                         value="{{ old('apellido', $user->apellido) }}">
                </div>

                <div class="input-group input-group-outline mb-3">
                  <label class="form-label ps-2 pt-2 pb-2 px-2">Email</label>
                  <input type="email" name="email" 
                         class="form-control ps-3" 
                         value="{{ old('email', $user->email) }}">
                </div>

                <div class="input-group input-group-outline mb-3">
                  <label class="form-label ps-2 pt-2 pb-2 px-2">Password (dejar en blanco para no cambiar)</label>
                  <input type="password" name="password" class="form-control ps-3">
                </div>

                <div class="input-group input-group-outline mb-3">
                  <label class="form-label ps-2 pt-2 pb-2 px-2">Confirmar Password</label>
                  <input type="password" name="password_confirmation" class="form-control ps-3">
                </div>

                <div class="input-group input-group-outline mb-3">
                  <label class="form-label ps-2 pt-2 pb-2 px-2">CI</label>
                  <input type="text" name="ci" 
                         class="form-control ps-3" 
                         value="{{ old('ci', $user->ci) }}">
                </div>

                <div class="input-group input-group-outline mb-3">
                  <label class="form-label ps-2 pt-2 pb-2 px-2">Datos Biométricos</label>
                  <textarea name="datos_biometricos" class="form-control ps-3"
                            rows="3">{{ old('datos_biometricos', $user->datos_biometricos) }}</textarea>
                </div>

                <div class="mb-3">
                  <label class="form-label ps-2 pt-2 pb-2 px-2">Estado</label>
                  <select name="estado" class="form-control ps-3">
                    <option value="1" {{ old('estado', $user->estado)=='1' ? 'selected':'' }}>Activo</option>
                    <option value="0" {{ old('estado', $user->estado)=='0' ? 'selected':'' }}>Inactivo</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label ps-2 pt-2 pb-2 px-2">Role</label>
                  <select name="role_id" class="form-control ps-3">
                    @foreach($roles as $role)
                      <option value="{{ $role->id }}"
                        {{ old('role_id', $user->role_id)==$role->id ? 'selected':'' }}>
                        {{ $role->nombre }}
                      </option>
                    @endforeach
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
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Inicialización de Chart.js (chart-bars, chart-line…)
  var ctxBar = document.getElementById("chart-bars").getContext("2d");
  new Chart(ctxBar, { /* … tu código Chart */ });

  var ctxLine = document.getElementById("chart-line").getContext("2d");
  new Chart(ctxLine, { /* … tu código Chart */ });
</script>
@endpush
