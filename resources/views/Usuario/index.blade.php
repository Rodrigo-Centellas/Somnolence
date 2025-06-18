@extends('layouts.app')

@section('content')
  {{-- Todo lo que estaba dentro de <main>…</main> tras el navbar, hasta justo antes del footer --}}
  
    {{-- Columna derecha (Reviews, Orders overview, Sales overview…) --}}
    <div class="col-lg-6 col-12 mt-4 mt-lg-0">
     <div class="card mb-4">
  <div class="card-header pb-0">
    <h6>Users Management</h6>
    <a href="user_create" class="btn btn-sm btn-primary">
    <i class="fa fa-plus me-1"></i> Add User
  </a>
  </div>
  <div class="card-body px-0 pt-0 pb-2">
    <div class="table-responsive p-0">
      <table class="table align-items-center mb-0">
        <thead>
          <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Joined</th>
            <th class="text-secondary opacity-7"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
            <tr>
              {{-- Avatar + Name + Email --}}
              <td>
                <div class="d-flex px-2 py-1">

                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="mb-0 text-sm">{{ $user->nombre }}</h6>
                    <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                  </div>
                </div>
              </td>

              {{-- Role --}}
              <td>
                <p class="text-xs font-weight-bold mb-0">{{ $user->role->nombre }}</p>
                
              </td>

              {{-- Status --}}
              <td class="align-middle text-center text-sm">
                @if($user->estado!='ocupado')
                  <span class="badge badge-sm bg-gradient-success">{{ $user->estado }}</span>
                @else
                  <span class="badge badge-sm bg-gradient-secondary">{{ $user->estado }}</span>
                @endif
              </td>

              {{-- Joined date --}}
              <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">
                  {{ $user->created_at->format('d/m/Y') }}
                </span>
              </td>

              {{-- Edit button --}}
              <td class="align-middle">
                <a href="{{ route('users.edit', $user) }}"
                   class="text-secondary font-weight-bold text-xs">
                  Edit
                </a>
                                 <!-- Delete -->
               <a href="#"
                  class="text-danger font-weight-bold text-xs"
                  onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">
                 Delete
               </a>
               <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}"
                     method="POST" class="d-inline"
                     onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?');">
                 @csrf
                 @method('DELETE')
                 <button type="submit"
                         class="btn btn-link text-danger p-0 m-0 text-xs align-baseline ps-2 pt-2 pb-2 px-2">
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
  </div>
@endsection

@push('scripts')
<script>
  // Inicialización de Chart.js (chart-bars, chart-line…)
  var ctxBar = document.getElementById("chart-bars").getContext("2d");
  new Chart(ctxBar, { /* … tal cual tu plantilla … */ });

  var ctxLine = document.getElementById("chart-line").getContext("2d");
  new Chart(ctxLine, { /* … tal cual tu plantilla … */ });
</script>
@endpush
