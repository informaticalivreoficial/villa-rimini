<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApartamentoRequest;
use App\Models\Apartamento;
use App\Models\ApartamentoGb;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

    public function update(ImovelRequest $request, $imovel)
    {      
        $deletePimovel = PortalImoveis::where('imovel', $imovel)->first();
        if($deletePimovel != null){
            $deletePimovel = PortalImoveis::where('imovel', $imovel)->get();
            foreach($deletePimovel as $delete){
                $delete->delete();
            }            
        } 

        $portaisRequest = $request->all();
        $portais = null;
        foreach($portaisRequest as $key => $value) {
            if(Str::is('portal_*', $key) == true){
                $f['portal'] = ltrim($key, 'portal_');
                $f['imovel'] = $imovel;
                $createPimovel = PortalImoveis::create($f);
                $createPimovel->save();
            }
        }

        $imovel = $this->imovelService->getImovelById($imovel);  
        $imovel->fill($request->all());

        $imovel->setVendaAttribute($request->venda);
        $imovel->setLocacaoAttribute($request->locacao);
        $imovel->setArCondicionadoAttribute($request->ar_condicionado);
        $imovel->setAquecedorsolarAttribute($request->aquecedor_solar);
        $imovel->setBarAttribute($request->bar);
        $imovel->setBibliotecaAttribute($request->biblioteca);
        $imovel->setChurrasqueiraAttribute($request->churrasqueira);
        $imovel->setEstacionamentoAttribute($request->estacionamento);
        $imovel->setEspacofitnessAttribute($request->espaco_fitness);
        $imovel->setCozinhaAmericanaAttribute($request->cozinha_americana);
        $imovel->setCozinhaPlanejadaAttribute($request->cozinha_planejada);
        $imovel->setDispensaAttribute($request->dispensa);
        $imovel->setEdiculaAttribute($request->edicula);
        $imovel->setEspacoFitnessAttribute($request->espaco_fitness);
        $imovel->setEscritorioAttribute($request->escritorio);
        $imovel->setArmarionauticoAttribute($request->armarionautico);
        $imovel->setFornodepizzaAttribute($request->fornodepizza);
        $imovel->setPortaria24hsAttribute($request->portaria24hs);
        $imovel->setQuintalAttribute($request->quintal);
        $imovel->setZeladoriaAttribute($request->zeladoria);
        $imovel->setSalaodejogosAttribute($request->salaodejogos);
        $imovel->setSaladetvAttribute($request->saladetv);
        $imovel->setAreadelazerAttribute($request->areadelazer);
        $imovel->setBalcaoamericanoAttribute($request->balcaoamericano);
        $imovel->setVarandagourmetAttribute($request->varandagourmet);
        $imovel->setBanheirosocialAttribute($request->banheirosocial);
        $imovel->setBrinquedotecaAttribute($request->brinquedoteca);
        $imovel->setPertodeescolasAttribute($request->pertodeescolas);
        $imovel->setCondominiofechadoAttribute($request->condominiofechado);
        $imovel->setInterfoneAttribute($request->interfone);
        $imovel->setSistemadealarmeAttribute($request->sistemadealarme);
        $imovel->setJardimAttribute($request->jardim);
        $imovel->setSalaodefestasAttribute($request->salaodefestas);
        $imovel->setPermiteanimaisAttribute($request->permiteanimais);
        $imovel->setQuadrapoliesportivaAttribute($request->quadrapoliesportiva);
        $imovel->setGeradoreletricoAttribute($request->geradoreletrico);
        $imovel->setBanheiraAttribute($request->banheira);
        $imovel->setLareiraAttribute($request->lareira);
        $imovel->setLavaboAttribute($request->lavabo);
        $imovel->setLavanderiaAttribute($request->lavanderia);
        $imovel->setElevadorAttribute($request->elevador);
        $imovel->setMobiliadoAttribute($request->mobiliado);
        $imovel->setVistaParaMarAttribute($request->vista_para_mar);
        $imovel->setPiscinaAttribute($request->piscina);
        $imovel->setVentiladorTetoAttribute($request->ventilador_teto);

        $imovel->save();
        $imovel->setSlug();

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return redirect()->back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $imovelImage = new ImovelGb();
                $imovelImage->imovel = $imovel->id;
                $imovelImage->path = $image->storeAs('imoveis/'. $imovel->tenant->uuid . '/' . $imovel->id, Str::slug($request->titulo) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $imovelImage->save();
                unset($imovelImage);
            }
        }

        return redirect()->route('imoveis.edit', [
            'imovel' => $imovel->id,
        ])->with(['color' => 'success', 'message' => 'Imóvel atualizado com sucesso!']);
    }

    public function apartamentoSetStatus(Request $request)
    {        
        $apartamento = Apartamento::find($request->id);
        $apartamento->status = $request->status;
        $apartamento->save();
        return response()->json(['success' => true]);
    }
}
