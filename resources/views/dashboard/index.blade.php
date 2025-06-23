@extends('layouts.app')

@push('styles')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="container-fluid py-4">

  <!-- Tarjetas de KPIs -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card bg-warning text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-0">{{ $totalUsuarios }}</h5>
            <small>Usuarios registrados</small>
          </div>
          <i class="fas fa-users fa-2x"></i>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-danger text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-0">{{ $totalTrips }}</h5>
            <small>Viajes totales</small>
          </div>
          <i class="fas fa-route fa-2x"></i>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-info text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-0">{{ $totalVehiculos }}</h5>
            <small>Vehículos disponibles</small>
          </div>
          <i class="fas fa-bus-alt fa-2x"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Gráficos -->
  <div class="row">
    <div class="col-lg-6 mb-4">
      <div class="card h-100">
        <div class="card-header"><h6>Viajes por mes</h6></div>
        <div class="card-body d-flex align-items-center">
          <div class="position-relative w-150 h-100">
            <canvas id="chartViajes" class="w-150 h-100"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-4">
      <div class="card h-100">
        <div class="card-header"><h6>Eventos por tipo</h6></div>
        <div class="card-body d-flex align-items-center">
          <div class="position-relative w-100 h-100">
            <canvas id="chartEventos" class="w-100 h-100"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-4">
      <div class="card h-100">
        <div class="card-header"><h6>Vehículos por capacidad</h6></div>
        <div class="card-body d-flex align-items-center">
          <div class="position-relative w-100 h-100">
            <canvas id="chartVehiculos" class="w-100 h-100"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-4">
      <div class="card h-100">
        <div class="card-header"><h6>Usuarios por rol</h6></div>
        <div class="card-body d-flex align-items-center">
          <div class="position-relative w-100 h-100">
            <canvas id="chartUsuarios" class="w-100 h-100"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-header"><h6>Resumen mensual de Viajes y Eventos</h6></div>
        <div class="card-body d-flex align-items-center">
          <div class="position-relative w-100 h-100">
            <canvas id="chartResumen" class="w-100 h-100"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  new Chart(document.getElementById('chartViajes'), {
    type: 'bar',
    data: {
      labels: {!! json_encode($viajesPorMes->pluck('mes')->reverse()) !!},
      datasets: [{
        label: 'Viajes',
        data: {!! json_encode($viajesPorMes->pluck('total')->reverse()) !!},
        backgroundColor: '#36a2eb'
      }]
    }
  });

  new Chart(document.getElementById('chartEventos'), {
    type: 'pie',
    data: {
      labels: {!! json_encode($eventosPorTipo->pluck('tipo')) !!},
      datasets: [{
        data: {!! json_encode($eventosPorTipo->pluck('total')) !!},
        backgroundColor: ['#ff6384', '#36a2eb', '#4bc0c0', '#ffcd56']
      }]
    }
  });

  new Chart(document.getElementById('chartVehiculos'), {
    type: 'bar',
    data: {
      labels: {!! json_encode($vehiculosPorCapacidad->pluck('capacidad')) !!},
      datasets: [{
        label: 'Cantidad',
        data: {!! json_encode($vehiculosPorCapacidad->pluck('total')) !!},
        backgroundColor: '#4bc0c0'
      }]
    }
  });

  new Chart(document.getElementById('chartUsuarios'), {
    type: 'doughnut',
    data: {
      labels: {!! json_encode($usuariosPorRol->pluck('rol')) !!},
      datasets: [{
        data: {!! json_encode($usuariosPorRol->pluck('total')) !!},
        backgroundColor: ['#36a2eb', '#ff6384', '#9966ff']
      }]
    }
  });

  new Chart(document.getElementById('chartResumen'), {
    type: 'line',
    data: {
      labels: {!! json_encode($resumenMensual->pluck('mes')) !!},
      datasets: [
        {
          label: 'Viajes',
          data: {!! json_encode($resumenMensual->pluck('viajes')) !!},
          borderColor: '#e91e63',
          backgroundColor: 'rgba(233, 30, 99, 0.1)',
          fill: true,
          tension: 0.4
        },
        {
          label: 'Eventos',
          data: {!! json_encode($resumenMensual->pluck('eventos')) !!},
          borderColor: '#3f51b5',
          backgroundColor: 'rgba(63, 81, 181, 0.1)',
          fill: true,
          tension: 0.4
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
@endpush
