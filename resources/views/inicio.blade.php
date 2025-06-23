@extends('layouts.app')

@section('content')
  {{-- Todo lo que estaba dentro de <main>…</main> tras el navbar, hasta justo antes del footer --}}
  
    {{-- Columna derecha (Reviews, Orders overview, Sales overview…) --}}
    <div class="col-lg-6 col-12 mt-4 mt-lg-0">
      {{-- … idéntico al original … --}}
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
