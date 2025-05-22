<!DOCTYPE html>
<html lang="en">
<head>
  @include('partials.head')
</head>
<body class="g-sidenav-show bg-gray-100">

  @include('partials.sidebar')

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('partials.navbar')

    <div class="container-fluid py-4">
      @yield('content')
    </div>
  </main>

  @include('partials.footer')

  {{-- Scripts comunes --}}
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

  {{-- Scripts específicos de cada vista --}}
  @stack('scripts')

  {{-- Control Center --}}
  <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.1.0') }}"></script>
</body>
</html>
