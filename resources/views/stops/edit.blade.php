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
      <h6 class="mb-0">Editar Parada</h6>
      <a href="{{ route('stops.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Volver
      </a>
    </div>
    <div class="card-body px-4 pt-4 pb-2">
      <form action="{{ route('stops.update', $stop) }}" method="POST">
        @csrf @method('PUT')

        {{-- Nombre --}}
        <div class="mb-4">
          <label class="form-label fw-bold">Nombre de la Parada</label>
          <input type="text" name="nombre" class="form-control"
                 value="{{ old('nombre', $stop->nombre) }}" required>
        </div>

        {{-- Coordenadas visibles --}}
        <div class="mb-3">
          <label class="form-label fw-bold">Coordenadas</label>
          <div class="row g-2">
            <div class="col">
              <input type="text" id="lat_vis" class="form-control" 
                     value="{{ old('latitud', $stop->latitud) }}" readonly>
            </div>
            <div class="col">
              <input type="text" id="lng_vis" class="form-control" 
                     value="{{ old('longitud', $stop->longitud) }}" readonly>
            </div>
          </div>
        </div>

        {{-- Guía y reset --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
          <small id="msg" class="text-muted">Haz clic en el mapa para mover la parada.</small>
          <button type="button" id="reset" class="btn btn-outline-secondary btn-sm">
            Reiniciar
          </button>
        </div>

        {{-- Mapa --}}
        <div class="map-wrapper mb-3"><div id="map"></div></div>

        {{-- Inputs ocultos --}}
        <input type="hidden" name="latitud"  id="lat" 
               value="{{ old('latitud', $stop->latitud) }}" required>
        <input type="hidden" name="longitud" id="lng" 
               value="{{ old('longitud', $stop->longitud) }}" required>

        <div class="text-end">
          <button class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Actualizar
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
  const initialLat = parseFloat(@json($stop->latitud)),
        initialLng = parseFloat(@json($stop->longitud));

  const map = L.map('map').setView([initialLat, initialLng], 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{ maxZoom:18 }).addTo(map);

  let marker = L.marker([initialLat, initialLng]).addTo(map);

  const latInput = document.getElementById('lat'),
        lngInput = document.getElementById('lng'),
        latVis   = document.getElementById('lat_vis'),
        lngVis   = document.getElementById('lng_vis'),
        msg      = document.getElementById('msg');

  map.on('click', e => {
    const { lat, lng } = e.latlng;
    marker.setLatLng(e.latlng);

    latInput.value = lat;
    lngInput.value = lng;
    latVis.value = lat.toFixed(6);
    lngVis.value = lng.toFixed(6);
    msg.textContent = 'Ubicación movida. Puedes actualizar o reiniciar.';
  });

  document.getElementById('reset').onclick = () => {
    marker.setLatLng([initialLat, initialLng]);
    latInput.value = initialLat;
    lngInput.value = initialLng;
    latVis.value = initialLat.toFixed(6);
    lngVis.value = initialLng.toFixed(6);
    msg.textContent = 'Haz clic en el mapa para mover la parada.';
    map.setView([initialLat, initialLng], 14);
  };
</script>
@endpush
