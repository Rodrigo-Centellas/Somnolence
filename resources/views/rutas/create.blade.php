@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
  <style>
    .dual-list { display:flex; gap:1rem; margin-bottom:1rem; }
    .dual-list ul {
      flex:1; min-height:200px; border:1px solid #ccc;
      border-radius:4px; padding:.5rem; list-style:none; overflow:auto;
    }
    .dual-list li {
      padding:.5rem; margin:.25rem 0;
      background:#f8f9fa; border:1px solid #ddd;
      border-radius:4px; cursor:move;
    }
    .map-wrapper { overflow:hidden; border-radius:12px; margin-bottom:1rem; }
    #map { width:100%; height:240px; }
  </style>
@endpush

@section('content')
<div class="col-lg-8 col-12 mt-4">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center pb-0">
      <h6 class="mb-0">Agregar Ruta</h6>
      <a href="{{ route('rutas.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Volver
      </a>
    </div>
    <div class="card-body px-4 pt-4 pb-2">
      <form action="{{ route('rutas.store') }}" method="POST">
        @csrf

        {{-- Nombre --}}
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>

        {{-- Origen/Destino --}}
        <div class="row mb-4">
          <div class="col-md-6">
            <label class="form-label">Origen (lat / lng)</label>
            <div class="input-group mb-2">
              <input readonly id="latO_vis" class="form-control" placeholder="Lat">
              <input readonly id="lngO_vis" class="form-control" placeholder="Lng">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Destino (lat / lng)</label>
            <div class="input-group mb-2">
              <input readonly id="latD_vis" class="form-control" placeholder="Lat">
              <input readonly id="lngD_vis" class="form-control" placeholder="Lng">
            </div>
          </div>
        </div>
        <div class="map-wrapper">
          <div id="map"></div>
        </div>
        <input type="hidden" name="latitud_origen"  id="latO" required>
        <input type="hidden" name="longitud_origen" id="lngO" required>
        <input type="hidden" name="latitud_destino"  id="latD" required>
        <input type="hidden" name="longitud_destino" id="lngD" required>

        {{-- Controles dual-list y reset mapa --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
          <small class="text-muted">Arrastra paradas entre las listas.</small>
          <div>
            <button type="button" id="reset-stops" class="btn btn-outline-secondary btn-sm me-2">
              Reset Paradas
            </button>
            <button type="button" id="reset-map" class="btn btn-outline-secondary btn-sm">
              Reset Mapa
            </button>
          </div>
        </div>

        {{-- Dual–list Paradas --}}
        <div class="dual-list">
          <ul id="available-stops">
            @foreach($allStops as $stop)
              <li data-id="{{ $stop->id }}">{{ $stop->nombre }}</li>
            @endforeach
          </ul>
          <ul id="selected-stops"></ul>
        </div>
        {{-- inputs ocultos generados --}}
        <div id="stops-inputs"></div>

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
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    // --- Leaflet para Origen/Destino ---
    const map = L.map('map').setView([-17.7833,-63.1833],6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{ maxZoom:18 }).addTo(map);

    let step=0, mO, mD, line;
    const latO = document.getElementById('latO'),
          lngO = document.getElementById('lngO'),
          latD = document.getElementById('latD'),
          lngD = document.getElementById('lngD'),
          latO_vis = document.getElementById('latO_vis'),
          lngO_vis = document.getElementById('lngO_vis'),
          latD_vis = document.getElementById('latD_vis'),
          lngD_vis = document.getElementById('lngD_vis');

    function updateCoords(){
      if(latO.value){
        latO_vis.value = parseFloat(latO.value).toFixed(6);
        lngO_vis.value = parseFloat(lngO.value).toFixed(6);
      }
      if(latD.value){
        latD_vis.value = parseFloat(latD.value).toFixed(6);
        lngD_vis.value = parseFloat(lngD.value).toFixed(6);
      }
    }
    function drawPath(){
      if(mO && mD){
        const pts = [mO.getLatLng(), mD.getLatLng()];
        if(line) line.setLatLngs(pts);
        else line = L.polyline(pts, { color:'#666' }).addTo(map);
      }
    }
    map.on('click', e => {
      const { lat, lng } = e.latlng;
      if(step === 0){
        mO ? mO.setLatLng(e.latlng) : mO = L.marker(e.latlng).addTo(map);
        latO.value = lat; lngO.value = lng;
        step = 1;
      } else if(step === 1){
        mD ? mD.setLatLng(e.latlng) : mD = L.marker(e.latlng).addTo(map);
        latD.value = lat; lngD.value = lng;
        drawPath();
        step = 2;
      }
      updateCoords();
    });

    // --- Dual–list SortableJS ---
    const avail = document.getElementById('available-stops'),
          sel   = document.getElementById('selected-stops'),
          inputs= document.getElementById('stops-inputs'),
          resetStops = document.getElementById('reset-stops'),
          resetMap   = document.getElementById('reset-map');

    const opts = {
      group: 'stops',
      animation: 150,
      onAdd:    syncStops,
      onUpdate: syncStops,
      onRemove: syncStops
    };
    Sortable.create(avail, opts);
    Sortable.create(sel, opts);

    function syncStops(){
      inputs.innerHTML = '';
      sel.querySelectorAll('li').forEach((li,i) => {
        const id = li.dataset.id;
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = `stops_order[${id}]`;
        inp.value = i+1;
        inputs.appendChild(inp);
      });
    }

    resetStops.addEventListener('click', () => {
      while(sel.firstChild) avail.appendChild(sel.firstChild);
      syncStops();
    });

    resetMap.addEventListener('click', () => {
      if(mO) map.removeLayer(mO);
      if(mD) map.removeLayer(mD);
      if(line) map.removeLayer(line);
      mO = mD = line = undefined;
      step = 0;
      [latO, lngO, latD, lngD].forEach(inp => inp.value = '');
      [latO_vis, lngO_vis, latD_vis, lngD_vis].forEach(inp => inp.value = '');
    });

    // inicializa inputs vacíos
    syncStops();
  </script>
@endpush
