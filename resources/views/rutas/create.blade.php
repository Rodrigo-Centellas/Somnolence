@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
  <style>
    .dual-list { display:flex; gap:1rem; }
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
            <input type="text" id="latO_vis" class="form-control" placeholder="Lat" readonly>
            <input type="text" id="lngO_vis" class="form-control" placeholder="Lng" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Destino (lat / lng)</label>
          <div class="input-group mb-2">
            <input type="text" id="latD_vis" class="form-control" placeholder="Lat" readonly>
            <input type="text" id="lngD_vis" class="form-control" placeholder="Lng" readonly>
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

      {{-- Dual-list stops --}}
      <div class="mb-4">
        <label class="form-label">Paradas</label>
        <div class="dual-list">
          <ul id="available-stops">
            @foreach($allStops as $stop)
              <li data-id="{{ $stop->id }}">{{ $stop->nombre }}</li>
            @endforeach
          </ul>
          <ul id="selected-stops"></ul>
        </div>
      </div>

      {{-- Hidden inputs for sync --}}
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
    // Leaflet
    const map = L.map('map').setView([-17.7833,-63.1833],6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:18}).addTo(map);
    let step=0, mO, mD, line;
    const latO=document.getElementById('latO'),
          lngO=document.getElementById('lngO'),
          latD=document.getElementById('latD'),
          lngD=document.getElementById('lngD'),
          latO_vis=document.getElementById('latO_vis'),
          lngO_vis=document.getElementById('lngO_vis'),
          latD_vis=document.getElementById('latD_vis'),
          lngD_vis=document.getElementById('lngD_vis');
    function updateCoords(){
      if(latO.value){ latO_vis.value=parseFloat(latO.value).toFixed(6); lngO_vis.value=parseFloat(lngO.value).toFixed(6);}
      if(latD.value){ latD_vis.value=parseFloat(latD.value).toFixed(6); lngD_vis.value=parseFloat(lngD.value).toFixed(6);}
    }
    function drawPath(){
      if(mO && mD){
        const pts=[mO.getLatLng(),mD.getLatLng()];
        if(line) line.setLatLngs(pts);
        else line=L.polyline(pts,{color:'#666'}).addTo(map);
      }
    }
    map.on('click',e=>{
      const {lat,lng}=e.latlng;
      if(step===0){
        if(mO) mO.setLatLng(e.latlng);
        else mO=L.marker(e.latlng).addTo(map);
        latO.value=lat; lngO.value=lng; step=1;
      } else if(step===1){
        if(mD) mD.setLatLng(e.latlng);
        else mD=L.marker(e.latlng).addTo(map);
        latD.value=lat; lngD.value=lng; drawPath(); step=2;
      }
      updateCoords();
    });

    // Dual-list
    const avail = document.getElementById('available-stops'),
          sel   = document.getElementById('selected-stops'),
          inputs= document.getElementById('stops-inputs');

    Sortable.create(avail, {
      group: { name:'stops', pull:true, put:false },
      animation:150
    });
    Sortable.create(sel, {
      group: { name:'stops', pull:true, put:true },
      animation:150,
      onAdd: sync, onUpdate: sync, onRemove: sync
    });

    function sync(){
      inputs.innerHTML = '';
      sel.querySelectorAll('li').forEach((li,i)=>{
        const id = li.dataset.id;
        const inp = document.createElement('input');
        inp.type='hidden'; inp.name=`stops_order[${id}]`; inp.value=i+1;
        inputs.appendChild(inp);
      });
    }
  </script>
@endpush
