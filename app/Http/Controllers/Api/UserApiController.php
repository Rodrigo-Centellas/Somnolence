<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserApiController extends Controller
{
    public function buscarPorCI($ci)
    {
        // Validar que el CI no esté vacío y sea un string válido
        if (empty($ci) || !is_string($ci)) {
            return response()->json(['mensaje' => 'CI inválido'], 400);
        }

        // Obtener los IDs de roles permitidos por nombre
        $rolesPermitidos = Role::whereIn('nombre', ['Gestor', 'Conductor'])->pluck('id');
        Log::debug('Roles permitidos: ' . $rolesPermitidos->implode(', '));

        // Buscar usuario con CI válido y rol permitido
        $usuario = User::where('ci', $ci)
            ->whereIn('role_id', $rolesPermitidos)
            ->first();

        if (!$usuario) {
            Log::debug('No se encontró un usuario con CI ' . $ci . ' o su rol no es permitido.');
            return response()->json(['mensaje' => 'Usuario no encontrado o no autorizado'], 404);
        }

        return response()->json([
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'apellido' => $usuario->apellido,
            'ci' => $usuario->ci,
            'foto_url' => asset($usuario->profile_photo_path),
            'rol' => $usuario->role_id,
        ]);
    }
}
