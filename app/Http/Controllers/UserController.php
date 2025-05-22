<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function showSignUp()
    {
        return view('sign_up');
    }
    public function index()
    {
        $users = User::all();
        return view('Usuario.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $roles = Role::all();
        return view('Usuario.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // 1. Validar los datos de entrada
    $data = $request->validate([
        'nombre'             => ['required', 'string', 'max:255'],
        'apellido'           => ['required', 'string', 'max:255'],
        'email'              => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password'           => ['required', 'string', 'min:3'],
        'ci'                 => ['nullable', 'string', 'max:100'],
        'datos_biometricos'  => ['nullable', 'string'],
        'estado'             => ['required', 'boolean'],
        'role_id'            => ['required', 'exists:roles,id'],
    ]);

    // 2. Hashear la contraseña
    $data['password'] = Hash::make($data['password']);

    // 3. Crear el usuario
    $user = User::create($data);

    // 4. Redirigir de vuelta con un mensaje de éxito
    return redirect()
        ->route('user_index')
        ->with('success', "Usuario “{$user->nombre} {$user->apellido}” creado correctamente.");
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
  public function edit(User $user)
    {
        $roles = Role::all();
        return view('Usuario.edit', compact('user','roles'));
    }

    // Procesar actualización
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nombre'             => ['required', 'string','max:255'],
            'apellido'           => ['required', 'string','max:255'],
            'email'              => ['required','string','email','max:255',"unique:users,email,{$user->id}"],
            'password'           => ['nullable','string','min:3'],
            'ci'                 => ['nullable','string','max:100'],
            'datos_biometricos'  => ['nullable','string'],
            'estado'             => ['required','boolean'],
            'role_id'            => ['required','exists:roles,id'],
        ]);

        // Sólo re-hashear si se proporciona nueva password
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete(); // o ->forceDelete() si quieres borrado definitivo
        return redirect()
            ->route('user_index')
            ->with('success', "Usuario “{$user->nombre} {$user->apellido}” eliminado.");
    }
}
