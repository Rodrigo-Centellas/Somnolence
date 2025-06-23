{{-- resources/views/map/index.blade.php --}}
@extends('layouts.app')

@push('styles')
  <!-- Mapbox CSS -->
  <link href="https://api.mapbox.com/mapbox-gl-js/v3.12.0/mapbox-gl.css" rel="stylesheet">
  <!-- Toastify CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

  <style>
    /* Animación fade + slide para eventos */
    @keyframes fadeSlideDown {
      0%   { opacity: 0; transform: translateY(-20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .new-event {
      animation: fadeSlideDown 0.4s ease-out;
      box-shadow: 0 0 10px rgba(0, 123, 255, .6);
      border-left: 4px solid #007bff;
    }

    /* Marcador de vehículo */
    .vehicle-marker {
      width: 32px; height: 32px;
      background-image: url('{{ asset("images/bus.png") }}');
      background-size: contain; background-repeat: no-repeat;
      box-shadow: 0 0 4px rgba(0,0,0,0.5);
      cursor: pointer;
    }

    /* Popup hover */
    .marker-popup { font-size:0.9rem; line-height:1.3; }
    .marker-popup strong { display:inline-block; width:80px; }
  </style>
@endpush

@section('content')
  <div class="row gx-0">
    <div class="col-lg-9">
      <div id="map" style="width:100%; height:calc(100vh - 100px)"></div>
    </div>
    <div class="col-lg-3">
      @include('partials.events-panel', ['eventos' => $eventos])
    </div>
  </div>
@endsection

@push('scripts')
  <!-- Socket.IO client -->
  <script src="https://cdn.socket.io/4.5.1/socket.io.min.js"></script>
  <!-- Mapbox JS -->
  <script src="https://api.mapbox.com/mapbox-gl-js/v3.12.0/mapbox-gl.js"></script>
  <!-- Toastify JS -->
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

  <!-- Audio notificación -->
  <audio id="event-sound" src="{{ asset('sounds/notification.mp3') }}" preload="auto"></audio>

  <!-- Datos para JS -->
  <script> window.tripsInfo     = @json($tripsInfo); </script>
  <script> window.lastLocations = @json($lastLocations); </script>

  <script>
    mapboxgl.accessToken = "{{ env('MAP_BOX_API') }}";
    const map = new mapboxgl.Map({
      container: 'map', style: 'mapbox://styles/mapbox/streets-v9',
      center: [-63.2, -17.8], zoom: 10
    });
    map.addControl(new mapboxgl.NavigationControl());
    map.scrollZoom.disable();

    // Pre-crea marcadores con la última ubicación
    const vehicleMarkers = {};
    for (let [trip_id, loc] of Object.entries(window.lastLocations)) {
      const coords = [parseFloat(loc.lng), parseFloat(loc.lat)];
      const info   = window.tripsInfo[trip_id] || {};
      const popup  = new mapboxgl.Popup({ closeButton:false, closeOnClick:false, offset:25 })
                        .setHTML(`
                          <div class="marker-popup">
                            <p><strong>Conductor:</strong> ${info.conductor||'N/A'}</p>
                            <p><strong>Placa:</strong> ${info.placa||'N/A'}</p>
                            <p><strong>Ruta:</strong> ${info.ruta||'N/A'}</p>
                          </div>
                        `);

      const el = document.createElement('div');
      el.className = 'vehicle-marker';
      el.addEventListener('mouseenter', () => popup.setLngLat(coords).addTo(map));
      el.addEventListener('mouseleave', () => popup.remove());

      vehicleMarkers[trip_id] = new mapboxgl.Marker({ element: el, anchor:'center' })
        .setLngLat(coords)
        .addTo(map);
    }

    // Función para “Ver en mapa” de eventos
    const eventMarkers = {};
    function attachViewInMapHandler(link) {
      const id  = link.dataset.id;
      const lat = parseFloat(link.dataset.lat);
      const lng = parseFloat(link.dataset.lng);
      link.addEventListener('click', e => {
        e.preventDefault();
        if (!eventMarkers[id]) {
          eventMarkers[id] = new mapboxgl.Marker()
            .setLngLat([lng, lat])
            .addTo(map);
        }
        map.flyTo({ center:[lng,lat], zoom:14, essential:true });
      });
    }
    document.querySelectorAll('.view-in-map').forEach(attachViewInMapHandler);

    const socket     = io("http://localhost:6001");
    const eventSound = document.getElementById('event-sound');

    // Llegada de nuevos eventos
    socket.on('events', payload => {
      eventSound.currentTime=0; eventSound.play().catch(console.error);
      Toastify({ text:`🔔 Nuevo evento: ${payload.data.tipo} – ${payload.data.mensaje}`,
                duration:4000, gravity:'top', position:'right',
                style:{background:'#007bff'} }).showToast();

      const lvl    = payload.data.nivel.charAt(0).toUpperCase()
                   + payload.data.nivel.slice(1).toLowerCase();
      const lvlCol = {'Informacion':'info','Advertencia':'warning','Critico':'danger'}[lvl]||'secondary';
      const typeCol= {'Velocidad':'warning','Ruta':'primary','Seguridad':'success','Emergencia':'danger'}[payload.data.tipo]||'secondary';

      const card = document.createElement('div');
      card.className = `card mb-3 bg-${lvlCol} bg-opacity-25 text-dark rounded shadow-sm new-event`;
      card.innerHTML = `
        <div class="d-flex justify-content-between align-items-center px-3 py-2">
          <span class="badge bg-${typeCol} text-white fw-bold">${payload.data.tipo.toUpperCase()}</span>
          <small class="fw-bold text-dark">${payload.data.hora}</small>
        </div>
        <div class="card-body px-3 py-2 bg-transparent">
          <p class="mb-1 fw-bold text-dark">Vehículo: ${payload.data.vehiculo}</p>
          <p class="mb-1 fw-bold text-dark">Ruta: ${payload.data.ruta}</p>
          <p class="mb-2 fw-bold text-dark">Mensaje: ${payload.data.mensaje}</p>
          <a href="#" class="fw-bold text-dark view-in-map"
             data-id="${payload.data.id}"
             data-lat="${payload.data.lat}"
             data-lng="${payload.data.lng}">Ver en mapa</a>
        </div>
      `;
      document.getElementById('events-container').prepend(card);
      attachViewInMapHandler(card.querySelector('.view-in-map'));
      card.addEventListener('animationend', () => card.classList.remove('new-event'));
    });

    // Actualización en tiempo real de GPS
    socket.on('gpslocations', payload => {
      const { trip_id, lat, lng } = payload.data;
      const coords = [parseFloat(lng), parseFloat(lat)];
      if (vehicleMarkers[trip_id]) {
        vehicleMarkers[trip_id].setLngLat(coords);
      }
    });
  </script>
@endpush
