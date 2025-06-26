{{-- resources/views/partials/events-panel.blade.php --}}
@php
    $levelContext = [
        'Informacion' => 'info',
        'Advertencia' => 'warning',
        'Critico'     => 'danger',
    ];
    $typeContext = [
        'Velocidad'  => 'warning',
        'Ruta'       => 'primary',
        'Seguridad'  => 'success',
        'Emergencia' => 'danger',
    ];
@endphp

<div class="card shadow-sm mb-4">
  <div class="card-header bg-transparent border-0 px-3 py-2">
    <h6 class="mb-0 fw-bold text-dark">Eventos</h6>
  </div>

  <div id="events-container"
       class="card-body p-0 overflow-auto px-3"
       style="max-height: calc(100vh - 220px);">
    @forelse($eventos as $evento)
      @php
        // Contextos de estilo
        $lvlKey     = ucfirst(strtolower($evento->nivel ?? ''));
        $lvlCol     = $levelContext[$lvlKey] ?? 'secondary';
        $typeCol    = $typeContext[$evento->tipo ?? ''] ?? 'secondary';

        // Valores seguros para placa, ruta y hora
        $placa      = $evento->trip?->vehicle?->placa ?? '—';
        $ruta       = $evento->trip?->route?->nombre ?? '—';
        $horaForm   = $evento->hora
                      ? \Carbon\Carbon::parse($evento->hora)->format('H:i')
                      : '--:--';
      @endphp

      <div class="card mb-3
                  bg-{{ $lvlCol }} bg-opacity-25
                  text-dark
                  rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center px-3 py-2">
          <span class="badge bg-{{ $typeCol }} text-white fw-bold">
            {{ strtoupper($evento->tipo ?? 'N/A') }}
          </span>
          <small class="fw-bold text-dark">
            {{ $horaForm }}
          </small>
        </div>
        <div class="card-body px-3 py-2 bg-transparent">
          <p class="mb-1 fw-bold text-dark">
            Vehículo: {{ $placa }}
          </p>
          <p class="mb-1 fw-bold text-dark">
            Ruta: {{ $ruta }}
          </p>
          <p class="mb-2 fw-bold text-dark">
            Mensaje: {{ $evento->mensaje ?? '—' }}
          </p>
          @if($evento->latitud && $evento->longitud)
            <a href="#"
               class="fw-bold text-dark view-in-map"
               data-id="{{ $evento->id }}"
               data-lat="{{ $evento->latitud }}"
               data-lng="{{ $evento->longitud }}">
              Ver en mapa
            </a>
          @else
            <span class="text-muted small">Sin ubicación</span>
          @endif
        </div>
      </div>
    @empty
      <p class="text-center text-muted small mb-0">No hay eventos recientes.</p>
    @endforelse
  </div>
</div>
