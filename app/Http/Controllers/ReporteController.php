<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\Response;
use App\Models\Trip;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{

public function index(Request $request)
{
    $query = Trip::with(['users', 'vehicle', 'route']);

    if ($request->filled('fecha_inicio')) {
        $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
    }

    if ($request->filled('fecha_fin')) {
        $query->whereDate('fecha_inicio', '<=', $request->fecha_fin);
    }

    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    if ($request->filled('vehiculo_id')) {
        $query->where('vehicle_id', $request->vehiculo_id);
    }

    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    $viajes = $query->get();

    $vehiculos = Vehicle::all();
    $usuarios = User::all();

    return view('reportes.reporte', compact('viajes', 'vehiculos', 'usuarios'));
}
public function exportExcel(Request $request)
{
    $query = Trip::with(['vehicle', 'users']);

    if ($request->filled('fecha_inicio')) {
        $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
    }
    if ($request->filled('fecha_fin')) {
        $query->whereDate('fecha_inicio', '<=', $request->fecha_fin);
    }
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }
    if ($request->filled('vehiculo_id')) {
        $query->where('vehicle_id', $request->vehiculo_id);
    }
    if ($request->filled('user_id')) {
        $query->whereHas('users', function ($q) use ($request) {
            $q->where('users.id', $request->user_id);
        });
    }

    $viajes = $query->get();

    $fileName = 'viajes_filtrados_' . now()->format('Ymd_His') . '.csv';
    $path = storage_path("app/public/{$fileName}");
    $writer = SimpleExcelWriter::create($path);

    foreach ($viajes as $viaje) {
        $writer->addRow([
            'ID'           => $viaje->id,
            'Fecha Inicio' => $viaje->fecha_inicio,
            'Estado'       => $viaje->estado,
            'Distancia'    => $viaje->distancia_recorrida,
            'Vehículo'     => optional($viaje->vehicle)->placa,
            'Usuarios'     => $viaje->users->pluck('nombre')->join(', ')
        ]);
    }

    $writer->close();

    return response()->download($path)->deleteFileAfterSend();
}



public function exportPdf(Request $request)
{
    $query = Trip::with(['vehicle', 'users']);

    if ($request->filled('fecha_inicio')) {
        $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
    }
    if ($request->filled('fecha_fin')) {
        $query->whereDate('fecha_inicio', '<=', $request->fecha_fin);
    }
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }
    if ($request->filled('vehiculo_id')) {
        $query->where('vehicle_id', $request->vehiculo_id);
    }
    if ($request->filled('user_id')) {
        $query->whereHas('users', function ($q) use ($request) {
            $q->where('users.id', $request->user_id);
        });
    }

    $viajes = $query->get();

    $pdf = Pdf::loadView('reportes.reporte_pdf', compact('viajes'));
    return $pdf->download('reporte_viajes_' . now()->format('Ymd_His') . '.pdf');
}
}
