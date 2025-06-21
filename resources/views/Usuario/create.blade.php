@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-10">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center pb-0">
          <h6 class="mb-0">Add User</h6>
          <a href="{{ route('user_index') }}" class="btn btn-sm btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Back
          </a>
        </div>
        <div class="card-body px-5 pt-4">
          <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nombre --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
            </div>

            {{-- Apellido --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Apellido</label>
              <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
            </div>

            {{-- Email --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            {{-- Password --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            {{-- CI --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">CI</label>
              <input type="text" name="ci" class="form-control" value="{{ old('ci') }}">
            </div>

            {{-- Datos Biométricos --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Datos Biométricos</label>
              <textarea name="datos_biometricos" class="form-control">{{ old('datos_biometricos') }}</textarea>
            </div>

            {{-- Estado --}}
            <div class="mb-3">
              <label class="form-label">Estado</label>
              <select name="estado" class="form-control">
                <option value="1" {{ old('estado')=='1' ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('estado')=='0' ? 'selected' : '' }}>Inactivo</option>
              </select>
            </div>

            {{-- Role --}}
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select name="role_id" class="form-control">
                @foreach($roles as $role)
                  <option value="{{ $role->id }}" {{ old('role_id')==$role->id ? 'selected' : '' }}>
                    {{ $role->nombre }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- FOTO --}}
            <div class="mb-4">
              <label class="form-label">Foto (obligatoria)</label><br>
              <img id="preview"
                   src="{{ asset('assets/img/placeholder.png') }}"
                   class="rounded mb-2"
                   style="width:150px;height:150px;object-fit:cover">
              <div class="mb-2">
                <button type="button" id="btnStartCamera" class="btn btn-sm btn-outline-primary me-2">
                  <i class="fa fa-camera me-1"></i> Tomar / Retomar
                </button>
                <button type="button" id="btnUpload" class="btn btn-sm btn-outline-secondary me-2">
                  <i class="fa fa-upload me-1"></i> Cargar Imagen
                </button>
                <button type="button" id="btnCapture" class="btn btn-sm btn-success" style="display:none;">
                  <i class="fa fa-check me-1"></i> Capturar
                </button>
              </div>
              <video id="video"
                     autoplay playsinline
                     style="display:none; width:150px;height:150px;object-fit:cover;border:1px solid #ccc;">
              </video>
              <input type="file"
                     name="photo"
                     id="photo"
                     accept="image/*"
                     capture="environment"
                     class="d-none"
                     required>
              <small class="text-muted">Máx. 2 MB · JPG/PNG.</small>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-1"></i> Save
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const video      = document.getElementById('video');
  const preview    = document.getElementById('preview');
  const fileInput  = document.getElementById('photo');
  const btnStart   = document.getElementById('btnStartCamera');
  const btnUpload  = document.getElementById('btnUpload');
  const btnCapture = document.getElementById('btnCapture');
  let stream;

  // 1. Abrir cámara
  btnStart.addEventListener('click', async () => {
    try {
      stream = await navigator.mediaDevices.getUserMedia({ video: true });
      video.srcObject = stream;
      video.style.display     = 'block';
      btnCapture.style.display = 'inline-block';
    } catch (e) {
      alert('No se pudo acceder a la cámara. Asegúrate de usar HTTPS y dar permisos.');
    }
  });

  // 2. Disparar selector de archivos
  btnUpload.addEventListener('click', () => {
    fileInput.click();
  });

  // 3. Procesar archivo seleccionado
  fileInput.addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file) return;
    if (stream) {
      stream.getTracks().forEach(t => t.stop());
      video.style.display     = 'none';
      btnCapture.style.display = 'none';
    }
    const url = URL.createObjectURL(file);
    preview.src = url;
  });

  // 4. Capturar foto de video
  btnCapture.addEventListener('click', () => {
    const canvas = document.createElement('canvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    stream.getTracks().forEach(t => t.stop());
    video.style.display     = 'none';
    btnCapture.style.display = 'none';

    const dataUrl = canvas.toDataURL('image/jpeg');
    preview.src = dataUrl;

    fetch(dataUrl)
      .then(res => res.blob())
      .then(blob => {
        const file = new File([blob], 'photo.jpg', { type: 'image/jpeg' });
        const dt   = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
      });
  });
</script>
@endpush
