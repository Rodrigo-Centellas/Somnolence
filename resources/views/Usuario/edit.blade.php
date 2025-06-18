@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-10">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center pb-0">
          <h6 class="mb-0">Edit User</h6>
          <a href="{{ route('user_index') }}" class="btn btn-sm btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Back
          </a>
        </div>
        <div class="card-body px-5 pt-4">
          <form action="{{ route('users.update', $user) }}"
                method="POST"
                enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- Nombre --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control"
                     value="{{ old('nombre', $user->nombre) }}" required>
            </div>

            {{-- Apellido --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Apellido</label>
              <input type="text" name="apellido" class="form-control"
                     value="{{ old('apellido', $user->apellido) }}" required>
            </div>

            {{-- Email --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control"
                     value="{{ old('email', $user->email) }}" required>
            </div>

            {{-- Password --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Password <small>(dejar en blanco para no cambiar)</small></label>
              <input type="password" name="password" class="form-control">
            </div>

            {{-- Confirmar Password --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Confirmar Password</label>
              <input type="password" name="password_confirmation" class="form-control">
            </div>

            {{-- CI --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">CI</label>
              <input type="text" name="ci" class="form-control"
                     value="{{ old('ci', $user->ci) }}">
            </div>

            {{-- Datos Biométricos --}}
            <div class="input-group input-group-outline mb-3">
              <label class="form-label">Datos Biométricos</label>
              <textarea name="datos_biometricos" class="form-control" rows="3">{{ old('datos_biometricos', $user->datos_biometricos) }}</textarea>
            </div>

            {{-- Estado --}}
            <div class="mb-3">
              <label class="form-label">Estado</label>
              <select name="estado" class="form-control">
                <option value="1" {{ old('estado', $user->estado)=='1' ? 'selected':'' }}>Activo</option>
                <option value="0" {{ old('estado', $user->estado)=='0' ? 'selected':'' }}>Inactivo</option>
              </select>
            </div>

            {{-- Role --}}
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select name="role_id" class="form-control">
                @foreach($roles as $role)
                  <option value="{{ $role->id }}"
                    {{ old('role_id', $user->role_id)==$role->id ? 'selected':'' }}>
                    {{ $role->nombre }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- FOTO: previsualización + cámara --}}
            <div class="mb-4">
              <label class="form-label">Foto de Perfil</label><br>
              <img id="preview" 
                   src="{{ $user->profile_photo_path
                              ? Storage::url($user->profile_photo_path)
                              : asset('assets/img/placeholder.png') }}"
                   class="rounded mb-2"
                   style="width:150px;height:150px;object-fit:cover">
              <div class="mb-2">
                <button type="button"
                        id="btnStartCamera"
                        class="btn btn-sm btn-outline-primary">
                  <i class="fa fa-camera me-1"></i> Tomar / Retomar
                </button>
                <button type="button"
                        id="btnCapture"
                        class="btn btn-sm btn-success"
                        style="display:none;">
                  <i class="fa fa-check me-1"></i> Capturar
                </button>
              </div>
              <video id="video"
                     autoplay playsinline
                     style="display:none;width:150px;height:150px;object-fit:cover;border:1px solid #ccc;">
              </video>
              <input type="file"
                     name="photo"
                     id="photo"
                     accept="image/*"
                     capture="environment"
                     class="d-none">
              <small class="text-muted">Máx. 2 MB · JPG/PNG.</small>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-1"></i> Update
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
  const btnCapture = document.getElementById('btnCapture');
  let stream;

  // Arrancar cámara
  btnStart.addEventListener('click', async () => {
    try {
      stream = await navigator.mediaDevices.getUserMedia({ video:true });
      video.srcObject = stream;
      video.style.display    = 'block';
      btnCapture.style.display = 'inline-block';
    } catch (e) {
      alert('Permiso denegado o HTTPS requerido.');
    }
  });

  // Capturar foto
  btnCapture.addEventListener('click', () => {
    const canvas = document.createElement('canvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    stream.getTracks().forEach(t=>t.stop());
    video.style.display    = 'none';
    btnCapture.style.display = 'none';

    const dataUrl = canvas.toDataURL('image/jpeg');
    preview.src = dataUrl;

    fetch(dataUrl).then(r=>r.blob()).then(blob=>{
      const file = new File([blob], 'photo.jpg', { type:'image/jpeg' });
      const dt   = new DataTransfer();
      dt.items.add(file);
      fileInput.files = dt.files;
    });
  });
</script>
@endpush
