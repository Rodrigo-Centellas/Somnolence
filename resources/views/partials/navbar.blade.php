<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
      </ol>
      <h6 class="font-weight-bolder mb-0">Dashboard</h6>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <div class="input-group">
          <span class="input-group-text text-body"><i class="fas fa-search"></i></span>
          <input type="text" class="form-control" placeholder="Type here...">
        </div>
      </div>
      <ul class="navbar-nav justify-content-end">
        {{-- Logout --}}
        <li class="nav-item d-flex align-items-center me-3">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link text-body font-weight-bold px-0 border-0 bg-transparent">
              <i class="fa fa-sign-out-alt me-sm-1"></i>
              <span class="d-sm-inline d-none">Logout</span>
            </button>
          </form>
        </li>
        {{-- Sign In --}}
        <li class="nav-item d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
            <i class="fa fa-user me-sm-1"></i>
            <span class="d-sm-inline d-none">Sign In</span>
          </a>
        </li>
        {{-- … notificaciones, toggler, etc. … --}}
      </ul>
    </div>
  </div>
</nav>
