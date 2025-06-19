@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<style>
  .map-wrapper { overflow:hidden; border-radius:12px; }
  #map        { width:100%; height:360px; }
</style>
@endpush

@section('content')
<div class="col-lg-8 col-12 mt-4">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center pb-0">
      <h6 class="mb-0">Agregar Parada</h6>
      <a href="{{ route('stops.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Volver
      </a>
    </div>
    <div class="card-body px-4 pt-4 pb-2">
      <form action="{{ route('stops.store') }}" method="POST">
        @csrf

        {{-- Nombre --}}
        <div class="mb-4">
          <label class="form-label fw-bold">Nombre de la Parada</label>
          <input type="text" name="nombre" class="form-control"
                 value="{{ old('nombre') }}" required>
        </div>

        {{-- Coordenadas visibles --}}
        <div class="mb-3">
          <label class="form-label fw-bold">Coordenadas</label>
          <div class="row g-2">
            <div class="col">
              <input type="text" id="lat_vis" class="form-control" placeholder="Latitud" readonly>
            </div>
            <div class="col">
              <input type="text" id="lng_vis" class="form-control" placeholder="Longitud" readonly>
            </div>
          </div>
        </div>

        {{-- Guía y reset --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
          <small id="msg" class="text-muted">Haz clic en el mapa para ubicar la parada.</small>
          <button type="button" id="reset" class="btn btn-outline-secondary btn-sm">
            Reiniciar
          </button>
        </div>

        {{-- Mapa --}}
        <div class="map-wrapper mb-3"><div id="map"></div></div>

        {{-- Inputs ocultos --}}
        <input type="hidden" name="latitud"  id="lat" required>
        <input type="hidden" name="longitud" id="lng" required>

        <div class="text-end">
          <button class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
  const map = L.map('map').setView([-17.7833,-63.1833], 6);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{ maxZoom:18 }).addTo(map);

  let marker = null;
  const latInput = document.getElementById('lat'),
        lngInput = document.getElementById('lng'),
        latVis   = document.getElementById('lat_vis'),
        lngVis   = document.getElementById('lng_vis'),
        msg      = document.getElementById('msg');

  map.on('click', e => {
    const { lat, lng } = e.latlng;
    if (marker) marker.setLatLng(e.latlng);
    else marker = L.marker(e.latlng).addTo(map);

    latInput.value = lat;
    lngInput.value = lng;
    latVis.value = lat.toFixed(6);
    lngVis.value = lng.toFixed(6);
    msg.textContent = 'Ubicación seleccionada. Puedes guardar o reiniciar.';
  });

  document.getElementById('reset').onclick = () => {
    if (marker) map.removeLayer(marker);
    marker = null;
    latInput.value = lngInput.value = '';
    latVis.value = lngVis.value = '';
    msg.textContent = 'Haz clic en el mapa para ubicar la parada.';
  };
</script>
@endpush
