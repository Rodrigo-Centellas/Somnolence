@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="height: calc(100vh - 70px);">
  <div id="map" style="width: 80%; height: 80%;"></div>
</div>
@endsection

@push('scripts')
<link href="https://api.mapbox.com/mapbox-gl-js/v3.12.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v3.12.0/mapbox-gl.js"></script>

<script>
mapboxgl.accessToken = "{{ env('MAP_BOX_API') }}";

const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v9',
    projection: 'globe',
    zoom: 10,
    center: [-63.2, -17.8] // Santa Cruz, Bolivia
});

map.addControl(new mapboxgl.NavigationControl());
map.scrollZoom.disable();

map.on('style.load', () => {
    map.setFog({});
});

const secondsPerRevolution = 240;
const maxSpinZoom = 5;
const slowSpinZoom = 3;
let userInteracting = false;
const spinEnabled = true;

function spinGlobe() {
    const zoom = map.getZoom();
    if (spinEnabled && !userInteracting && zoom < maxSpinZoom) {
        let distancePerSecond = 360 / secondsPerRevolution;
        if (zoom > slowSpinZoom) {
            const zoomDif = (maxSpinZoom - zoom) / (maxSpinZoom - slowSpinZoom);
            distancePerSecond *= zoomDif;
        }
        const center = map.getCenter();
        center.lng -= distancePerSecond;
        map.easeTo({ center, duration: 1000, easing: (n) => n });
    }
}

map.on('mousedown', () => { userInteracting = true; });
map.on('dragstart', () => { userInteracting = true; });
map.on('moveend', () => { spinGlobe(); });

spinGlobe();
</script>
@endpush
