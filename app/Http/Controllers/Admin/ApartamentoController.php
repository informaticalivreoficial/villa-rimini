<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApartamentoRequest;
use App\Models\Apartamento;
use App\Models\ApartamentoGb;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function imageWatermark(Request $request)
    {
        $apartamento = Apartamento::where('id', $id)->first();
        $apartamento->fill($request->all());
        $imagensGallery = ApartamentoGb::where('apartamento', $request->id)->get();        
        if(!empty($apartamento->marcadagua) && !empty($imagensGallery)){
            foreach($imagensGallery as $imagem){   
                $img = Image::make(Storage::get($imagem->path));  
                /* insert watermark at bottom-right corner with 10px offset */
                $img->insert(Storage::get($apartamento->marcadagua), 'bottom-right', 10, 10);                
                $img->save(storage_path('app/public/'.$imagem->path));
                $img->encode('png');  
            }
            $affected = DB::table('apartamento_gbs')
                    ->where('apartamento', '=', $request->id)
                    ->update(array('marcadagua' => 1));            
            unset($affected);

            $apartamento->exibirmarcadagua = 1;
            $apartamento->save();

            $json = "Marca D´agua inserida com sucesso!";
            return response()->json(['success' => $json]);
        }else{
             $json = "Erro ao inserir a Marca D´agua!";
             return response()->json(['error' => $json]);
        }
    }
}
