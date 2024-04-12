<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApartamentoRequest;
use App\Models\Apartamento;
use App\Models\ApartamentoGb;
use App\Models\Configuracoes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Image;

class ApartamentoController extends Controller
{
    public function index()
    {
        $apartamentos = Apartamento::orderby('titulo', 'DESC')->paginate(25);
        return view('admin.apartamentos.index', [
            'apartamentos' => $apartamentos,
        ]);
    }

    public function create()
    {
        return view('admin.apartamentos.create');
    }

    public function store(ApartamentoRequest $request)
    {
        $criarApartamento = Apartamento::create($request->all());
        $criarApartamento->fill($request->all());

        $criarApartamento->setSlug();

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return redirect()->back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }
        
        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $apartamentoGb = new ApartamentoGb();
                $apartamentoGb->apartamento = $criarApartamento->id;
                $apartamentoGb->path = $image->storeAs(env('AWS_PASTA') . 'apartamentos/'. $criarApartamento->id, Str::slug($request->titulo) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $apartamentoGb->save();
                unset($apartamentoGb);
            }
        }
        
        return redirect()->route('apartamentos.edit', [
            'id' => $criarApartamento->id
        ])->with(['color' => 'success', 'message' => 'Apartamento cadastrado com sucesso!']);
    }

    public function edit($id)
    {
        $apartamento = Apartamento::where('id', $id)->first();        
        return view('admin.apartamentos.edit', [
            'apartamento' => $apartamento
        ]);
    }

    public function update(ApartamentoRequest $request, $id)
    {     
        $apartamento = Apartamento::where('id', $id)->first(); 
        $apartamento->fill($request->all());

        $apartamento->setArCondicionadoAttribute($request->ar_condicionado);
        $apartamento->setEstacionamentoAttribute($request->estacionamento);
        $apartamento->setEspacofitnessAttribute($request->espaco_fitness);
        $apartamento->setTelefoneAttribute($request->telefone);
        $apartamento->setLareiraAttribute($request->lareira);
        $apartamento->setElevadorAttribute($request->elevador);
        $apartamento->setVistaParaMarAttribute($request->vista_para_mar);
        $apartamento->setVentiladorTetoAttribute($request->ventilador_teto);        
        $apartamento->setValorCafeAttribute($request->valor_cafe);
        $apartamento->setValorCafeAlmocoAttribute($request->valor_cafe_almoco);
        $apartamento->setValorCafeJantaAttribute($request->valor_cafe_janta);
        $apartamento->setValorCri05Attribute($request->valor_cri_0_5);
        $apartamento->setCafeManhaAttribute($request->cafe_manha);
        $apartamento->setCofreIndividualAttribute($request->cofre_individual);
        $apartamento->setFrigobarAttribute($request->frigobar);
        $apartamento->setServicoQuartoAttribute($request->servico_quarto);
        $apartamento->setWifiAttribute($request->wifi);

        $apartamento->save();
        $apartamento->setSlug();

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return redirect()->back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $apartamentoGb = new ApartamentoGb();
                $apartamentoGb->apartamento = $apartamento->id;
                $apartamentoGb->path = $image->storeAs(env('AWS_PASTA') . 'apartamentos/'. $apartamento->id, Str::slug($request->titulo) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $apartamentoGb->save();
                unset($apartamentoGb);
            }
        }

        return redirect()->route('apartamentos.edit', [
            'id' => $apartamento->id,
        ])->with(['color' => 'success', 'message' => 'Apartamento atualizado com sucesso!']);
    }

    public function imageSetCover(Request $request)
    {
        $imageSetCover = ApartamentoGb::where('id', $request->image)->first();
        $allImage = ApartamentoGb::where('apartamento', $imageSetCover->apartamento)->get();

        foreach ($allImage as $image) {
            $image->cover = null;
            $image->save();
        }

        $imageSetCover->cover = true;
        $imageSetCover->save();

        $json = [
            'success' => true,
        ];

        return response()->json($json);
    }

    public function imageRemove(Request $request)
    {
        $imageDelete = ApartamentoGb::where('id', $request->image)->first();

        Storage::delete($imageDelete->path);
        $imageDelete->delete();

        $json = [
            'success' => true,
        ];
        return response()->json($json);
    }

    public function apartamentoSetStatus(Request $request)
    {        
        $apartamento = Apartamento::find($request->id);
        $apartamento->status = $request->status;
        $apartamento->save();
        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $apartamento = Apartamento::find($request->id);
        $apartamentoGb = ApartamentoGb::where('apartamento', $request->id)->first();
        $nome = \App\Helpers\Renato::getPrimeiroNome(Auth::user()->name);
        if(!empty($apartamento) && !empty($apartamentoGb)){
            $json = "<b>$nome</b> você tem certeza que deseja excluir este apartamento? Ele possui imagens e todas serão excluídas!";
            return response()->json(['error' => $json,'id' => $apartamento->id]);
        }elseif(!empty($apartamento) && empty($apartamentoGb)){
            $json = "<b>$nome</b> você tem certeza que deseja excluir este apartamento?";
            return response()->json(['error' => $json,'id' => $apartamento->id]);
        }else{
            return response()->json(['success' => true]);
        }
    }
    
    public function deleteon(Request $request)
    {
        $apartamento = Apartamento::find($request->apartamento_id);
        $imageDelete = ApartamentoGb::where('apartamento', $request->apartamento_id)->get();
        $apartamentoR = $apartamento->titulo;
        if(!empty($apartamento)){
            if(!empty($imageDelete)){
                foreach($imageDelete as $imgGB){
                    Storage::delete($imgGB->path);
                    $imgGB->delete();
                }                
                Storage::deleteDirectory('apartamentos/'. $request->apartamento_id);
                $apartamento->delete();
            }
            $apartamento->delete();
        }
        return redirect()->route('apartamentos.index')->with(['color' => 'success', 'message' => 'O apartamento '.$apartamentoR.' foi removido com sucesso!']);
    }
}
