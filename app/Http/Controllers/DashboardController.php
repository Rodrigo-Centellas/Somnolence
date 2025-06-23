<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Trip;
use App\Models\Event;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI Totales
        $totalUsuarios = User::count();
        $totalVehiculos = Vehicle::count();
        $totalTrips = Trip::count();

        // Viajes por mes
        $viajesPorMes = DB::table('trips')
            ->select(DB::raw("TO_CHAR(fecha_inicio, 'Mon') as mes"), DB::raw('COUNT(*) as total'))
            ->groupBy(DB::raw("TO_CHAR(fecha_inicio, 'Mon')"))
            ->orderByRaw("MIN(fecha_inicio)")
            ->get();

        // Eventos por tipo
        $eventosPorTipo = DB::table('events')
            ->select('tipo', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo')
            ->get();

        // Vehículos por capacidad
        $vehiculosPorCapacidad = DB::table('vehicles')
            ->select('capacidad', DB::raw('COUNT(*) as total'))
            ->groupBy('capacidad')
            ->get();

        // Usuarios por rol
        $usuariosPorRol = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('roles.nombre as rol', DB::raw('COUNT(*) as total'))
            ->groupBy('roles.nombre')
            ->get();

        // Resumen mensual: viajes y eventos por mes
        $resumenMensual = DB::select("
            SELECT
                m.mes,
                COALESCE(t.viajes, 0) AS viajes,
                COALESCE(e.eventos, 0) AS eventos
            FROM (
                SELECT TO_CHAR(fecha_inicio, 'Mon') AS mes
                FROM trips
                GROUP BY TO_CHAR(fecha_inicio, 'Mon')
            ) m
            LEFT JOIN (
                SELECT TO_CHAR(fecha_inicio, 'Mon') AS mes, COUNT(*) AS viajes
                FROM trips
                GROUP BY TO_CHAR(fecha_inicio, 'Mon')
            ) t ON t.mes = m.mes
            LEFT JOIN (
                SELECT TO_CHAR(fecha, 'Mon') AS mes, COUNT(*) AS eventos
                FROM events
                GROUP BY TO_CHAR(fecha, 'Mon')
            ) e ON e.mes = m.mes
            ORDER BY TO_DATE(m.mes, 'Mon')
        ");
        $resumenMensual = collect($resumenMensual);

        return view('dashboard.index', compact(
            'totalUsuarios',
            'totalVehiculos',
            'totalTrips',
            'viajesPorMes',
            'eventosPorTipo',
            'vehiculosPorCapacidad',
            'usuariosPorRol',
            'resumenMensual'
        ));
    }
}
