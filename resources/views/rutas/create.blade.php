@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<style>
  .map-wrapper {overflow:hidden;border-radius:12px;}
  #map        {width:100%;height:360px;}
  .markerO, .markerD{
      width:24px;height:24px;border-radius:50%;color:#fff;
      font-weight:bold;font-size:14px;line-height:24px;text-align:center;
  }
  .markerO{background:#28a745;}  /* verde */
  .markerD{background:#dc3545;}  /* rojo  */
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

    {{-- Box 1 – Nombre --}}
    <div class="mb-4">
      <label class="form-label fw-bold">Nombre</label>
      <input type="text" class="form-control" name="nombre" id="nombre"
             placeholder="Ej: Ruta Mercado — Centro" required>
    </div>

    {{-- Box 2 – Origen --}}
    <div class="mb-3">
      <label class="form-label fw-bold">Origen (lat / lng)</label>
      <div class="row g-2">
        <div class="col"><input class="form-control" id="latO_vis" readonly></div>
        <div class="col"><input class="form-control" id="lngO_vis" readonly></div>
      </div>
    </div>

    {{-- Box 3 – Destino --}}
    <div class="mb-4">
      <label class="form-label fw-bold">Destino (lat / lng)</label>
      <div class="row g-2">
        <div class="col"><input class="form-control" id="latD_vis" readonly></div>
        <div class="col"><input class="form-control" id="lngD_vis" readonly></div>
      </div>
    </div>

    {{-- Guía + reset --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
      <small id="msg" class="text-muted">Primer clic → Origen • Segundo clic → Destino.</small>
      <button type="button" class="btn btn-outline-secondary btn-sm" id="reset">Reiniciar</button>
    </div>

    {{-- Mapa --}}
    <div class="map-wrapper mb-3"><div id="map"></div></div>

    {{-- Inputs ocultos --}}
    <input type="hidden" name="latitud_origen"   id="latO" required>
    <input type="hidden" name="longitud_origen"  id="lngO" required>
    <input type="hidden" name="latitud_destino"  id="latD" required>
    <input type="hidden" name="longitud_destino" id="lngD" required>

    <div class="text-end">
      <button class="btn btn-primary"><i class="fa fa-save me-1"></i> Guardar</button>
    </div>
   </form>
  </div>
 </div>
</div>
@endsection


@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
/* === Leaflet básico === */
const map = L.map('map').setView([-17.7833,-63.1833], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:18}).addTo(map);

/* Iconos Div */
const iconO = L.divIcon({className:'markerO', html:'O', iconSize:[24,24]});
const iconD = L.divIcon({className:'markerD', html:'D', iconSize:[24,24]});

let step=0, mO=null, mD=null, line=null;

/* inputs */
const latO = document.getElementById('latO'),
      lngO = document.getElementById('lngO'),
      latD = document.getElementById('latD'),
      lngD = document.getElementById('lngD');
const latO_vis=document.getElementById('latO_vis'),
      lngO_vis=document.getElementById('lngO_vis'),
      latD_vis=document.getElementById('latD_vis'),
      lngD_vis=document.getElementById('lngD_vis');

function updateVis(){
  if(latO.value){latO_vis.value=(+latO.value).toFixed(6);lngO_vis.value=(+lngO.value).toFixed(6);}
  if(latD.value){latD_vis.value=(+latD.value).toFixed(6);lngD_vis.value=(+lngD.value).toFixed(6);}
}
function drawLine(){
  if(mO && mD){
    const pts=[mO.getLatLng(),mD.getLatLng()];
    line ? line.setLatLngs(pts) : line=L.polyline(pts,{color:'#666'}).addTo(map);
  }
}
function reset(){
  [mO,mD,line].forEach(l=>l && map.removeLayer(l));
  mO=mD=line=null;step=0;
  [latO,lngO,latD,lngD,latO_vis,lngO_vis,latD_vis,lngD_vis].forEach(i=>i.value='');
  msg.textContent='Primer clic → Origen • Segundo clic → Destino.';
}
document.getElementById('reset').onclick=reset;

/* Clic en mapa */
map.on('click', e=>{
  const {lat,lng}=e.latlng;
  if(step===0){
     mO ? mO.setLatLng(e.latlng) : mO=L.marker(e.latlng,{icon:iconO}).addTo(map);
     latO.value=lat;lngO.value=lng;step=1;
     msg.textContent='Ahora clic para Destino.';
  }else if(step===1){
     mD ? mD.setLatLng(e.latlng) : mD=L.marker(e.latlng,{icon:iconD}).addTo(map);
     latD.value=lat;lngD.value=lng;step=2;
     msg.textContent='¡Listo! Guarda o reinicia.';
     drawLine();
  }
  updateVis();
});
</script>
@endpush
