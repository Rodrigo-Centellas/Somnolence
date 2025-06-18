<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /* ------------------------------------------------------------------
     |  LISTAR
     *------------------------------------------------------------------*/
    public function index()
    {
        $users = User::all();
        return view('Usuario.index', compact('users'));
    }

    /* ------------------------------------------------------------------
     |  FORMULARIO CREATE
     *------------------------------------------------------------------*/
    public function create()
    {
        $roles = Role::all();
        return view('Usuario.create', compact('roles'));
    }

    /* ------------------------------------------------------------------
     |  GUARDAR NUEVO USUARIO
     *------------------------------------------------------------------*/
    public function store(Request $request)
    {
        /* 1. Validación */
        $data = $request->validate([
            'nombre'    => ['required', 'string', 'max:255'],
            'apellido'  => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:3'],
            'photo'     => ['required', 'image', 'mimes:jpeg,png', 'max:2048'],
            'ci'        => ['nullable', 'string', 'max:100'],
            'datos_biometricos' => ['nullable', 'string'],
            'estado'    => ['required', 'boolean'],
            'role_id'   => ['required', 'exists:roles,id'],
        ]);

        /* 2. Subir foto */
        $path = $request->file('photo')
                        ->store('users', 'public');   // storage/app/public/users
        $data['profile_photo_path'] = $path;

        /* 3. Hashear contraseña */
        $data['password'] = Hash::make($data['password']);

        /* 4. Crear usuario */
        $user = User::create($data);

        return redirect()
            ->route('user_index')
            ->with('success', "Usuario «{$user->nombre} {$user->apellido}» creado correctamente.");
    }

    /* ------------------------------------------------------------------
     |  FORMULARIO EDIT
     *------------------------------------------------------------------*/
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('Usuario.edit', compact('user', 'roles'));
    }

    /* ------------------------------------------------------------------
     |  ACTUALIZAR
     *------------------------------------------------------------------*/
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nombre'    => ['required', 'string', 'max:255'],
            'apellido'  => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'password'  => ['nullable', 'string', 'min:3'],
            'photo'     => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
            'ci'        => ['nullable', 'string', 'max:100'],
            'datos_biometricos' => ['nullable', 'string'],
            'estado'    => ['required', 'boolean'],
            'role_id'   => ['required', 'exists:roles,id'],
        ]);

        /* Foto nueva */
        if ($request->hasFile('photo')) {
            // borrar la anterior si existe
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $data['profile_photo_path'] = $request->file('photo')
                                                 ->store('users', 'public');
        }

        /* Password opcional */
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('user_index')
            ->with('success', "Usuario «{$user->nombre} {$user->apellido}» actualizado.");
    }

    /* ------------------------------------------------------------------
     |  ELIMINAR
     *------------------------------------------------------------------*/
    public function destroy(User $user)
    {
        // borrar foto asociada
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        return redirect()
            ->route('user_index')
            ->with('success', "Usuario «{$user->nombre} {$user->apellido}» eliminado.");
    }
}
