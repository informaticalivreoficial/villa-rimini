<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Configuracoes as ConfiguracoesRequest;
use App\Models\Configuracoes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Support\Cropper;
use App\Models\Estados;
use App\Models\Cidades;
use App\Models\Template;
use Carbon\Carbon;

class ConfigController extends Controller
{
    public function editar()
    {
        $config = Configuracoes::where('id', '1')->first();
        $estados = Estados::orderBy('estado_nome', 'ASC')->get();
        $cidades = Cidades::orderBy('cidade_nome', 'ASC')->get();

        $templates = Template::orderBy('created_at', 'DESC')
                ->available()
                ->get();

        $sitemap = Carbon::createFromFormat('Y-m-d', $config->sitemap_data);
        $datahoje = Carbon::now();
        $diferenca = $datahoje->diffInDays($sitemap); // saída: X dias

        $feeddata = Carbon::createFromFormat('Y-m-d', $config->rss_data);
        $feeddatahoje = Carbon::now();
        $feeddatadiferenca = $feeddatahoje->diffInDays($feeddata); // saída: X dias

        return view('admin.configuracoes',[
            'config' => $config,
            'estados' => $estados,
            'cidades' => $cidades,
            'diferenca' => $diferenca,
            'templates' => $templates,
            'feeddatadiferenca' => $feeddatadiferenca
        ]);
    }

    public function fetchCity(Request $request)
    {
        $data['cidades'] = Cidades::where("estado_id",$request->estado_id)->get(["cidade_nome", "cidade_id"]);
        return response()->json($data);
    }

    public function update(ConfiguracoesRequest $request, $id)
    {
        $config = Configuracoes::where('id', $id)->first(); 

        if(!empty($request->file('metaimg'))){
            Storage::delete($config->metaimg);
            $config->metaimg = '';
        }
        
        if(!empty($request->file('logomarca'))){
            Storage::delete($config->logomarca);
            $config->logomarca = '';
        }
        
        if(!empty($request->file('logomarca_admin'))){
            Storage::delete($config->logomarca_admin);
            $config->logomarca_admin = '';
        }
        
        if(!empty($request->file('favicon'))){
            Storage::delete($config->favicon);
            $config->favicon = '';
        }
        
        if(!empty($request->file('marcadagua'))){
            Storage::delete($config->marcadagua);
            $config->marcadagua = '';
        }
        
        if(!empty($request->file('imgheader'))){
            Storage::delete($config->imgheader);
            $config->imgheader = '';
        }
        
        $config->fill($request->all());
        
        if(!empty($request->file('metaimg'))){
            $config->metaimg = $request->file('metaimg')->storeAs(env('AWS_PASTA') . 'configuracoes', 'metaimg-'.Str::slug($request->nomedosite)  . '.' . $request->file('metaimg')->extension());
        }
        
        if(!empty($request->file('logomarca'))){
            $config->logomarca = $request->file('logomarca')->storeAs(env('AWS_PASTA') . 'configuracoes', 'logomarca-'.Str::slug($request->nomedosite)  . '.' . $request->file('logomarca')->extension());
        }
        
        if(!empty($request->file('logomarca_admin'))){
            $config->logomarca_admin = $request->file('logomarca_admin')->storeAs(env('AWS_PASTA') . 'configuracoes', 'logomarca-admin-'.Str::slug($request->nomedosite)  . '.' . $request->file('logomarca_admin')->extension());
        }
        
        if(!empty($request->file('favicon'))){
            $config->favicon = $request->file('favicon')->storeAs(env('AWS_PASTA') . 'configuracoes', 'favivon-'.Str::slug($request->nomedosite)  . '.' . $request->file('favicon')->extension());
        }
        
        if(!empty($request->file('marcadagua'))){
            $config->marcadagua = $request->file('marcadagua')->storeAs(env('AWS_PASTA') . 'configuracoes', 'marcadagua-'.Str::slug($request->nomedosite)  . '.' . $request->file('marcadagua')->extension());
        }
        
        if(!empty($request->file('imgheader'))){
            $config->imgheader = $request->file('imgheader')->storeAs(env('AWS_PASTA') . 'configuracoes', 'imgheader-'.Str::slug($request->nomedosite)  . '.' . $request->file('imgheader')->extension());
        }
        
        if(!$config->save()){
            return redirect()->back()->withInput()->withErrors('Erro');
        }

        return redirect()->route('configuracoes.editar', $config->id)->with([
            'color' => 'success', 
            'message' => 'Configurações atualizadas com sucesso!'
        ]);
    }
}
