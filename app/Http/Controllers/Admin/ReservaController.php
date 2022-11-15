<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartamento;
use App\Models\Reservas;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function pendentes()
    {
        $reservas = Reservas::orderby('created_at', 'DESC')->available()->paginate(25);
        return view('admin.reservas.pendentes', [
            'reservas' => $reservas,
        ]);
    }

    public function finalizadas()
    {
        $reservas = Reservas::orderby('created_at', 'DESC')->unavailable()->paginate(25);
        return view('admin.reservas.finalizadas', [
            'reservas' => $reservas,
        ]);
    }

    public function create()
    {
        return view('admin.reservas.create');
    }

    public function edit($id)
    {
        $reserva = Reservas::where('id', $id)->first();  
        $apartamentos = Apartamento::available()->get();     
        return view('admin.reservas.edit', [
            'reserva' => $reserva,
            'apartamentos' => $apartamentos
        ]);
    }
}
