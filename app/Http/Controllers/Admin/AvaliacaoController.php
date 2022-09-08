<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avaliacoes;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    public function index()
    {
        $avaliacoes = Avaliacoes::orderby('created_at', 'DESC')->paginate(35);
        return view('admin.avaliacoes.index', [
            'avaliacoes' => $avaliacoes,
        ]);
    }

    public function avaliacoesSetStatus(Request $request)
    {        
        $avaliacao = Avaliacoes::find($request->id);
        $avaliacao->status = $request->status;
        $avaliacao->save();
        return response()->json(['success' => true]);
    }
}
