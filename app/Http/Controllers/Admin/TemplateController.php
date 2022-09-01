<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Support\Cropper;
use Illuminate\Support\Facades\Redirect;


class TemplateController extends Controller
{
    public function __construct()
    {        
        $this->middleware(['can:templates']);
    }

    public function index()
    {
        $templates = Template::orderBy('created_at', 'DESC')->orderBy('status', 'ASC')->paginate(25);
        return view('admin.templates.index', [
            'templates' => $templates,
        ]);
    }

    public function create()
    {
        return view('admin.templates.create');
    }
    
    public function store(Request $request)
    {
        $templateCreate = Template::create($request->all()); 

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if(!empty($request->file('imagem'))){
            $templateCreate->imagem = $request->file('imagem')->storeAs('templates/', $request->name  . '-' . str_replace('.', '', microtime(true)) . '.' . $request->file('imagem')->extension());
            $templateCreate->save();
        }

        return Redirect::route('templates.edit', $templateCreate->id)->with(['color' => 'success', 'message' => 'Template cadastrado com sucesso!']);
    }    
    
    public function edit($id)
    {
        $template = Template::where('id', $id)->first(); 
        
        return view('admin.templates.edit', [
            'template' => $template
        ]);
    }

    public function update(Request $request, $id)
    {
        $template = Template::where('id', $id)->first();
        
        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if(!empty($request->file('imagem'))){
            Storage::delete($template->imagem);
            Cropper::flush($template->imagem);
            $template->imagem = '';
        }

        $template->fill($request->all());

        if(!empty($request->file('imagem'))){
            $template->imagem = $request->file('imagem')->storeAs('templates/', $request->name  . '-' . str_replace('.', '', microtime(true)) . '.' . $request->file('imagem')->extension());
        }

        if(!$template->save()){
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Erro ao salvar Template',
            ]);
        }        

        return Redirect::route('templates.edit', $template->id)->with([
            'color' => 'success', 
            'message' => 'Template atualizado com sucesso!'
        ]);
    }

    public function templateSetStatus(Request $request)
    {        
        $template = Template::find($request->id);
        $template->status = $request->status;
        $template->save();
        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $template = Template::where('id', $request->id)->first();
        $nome = getPrimeiroNome(Auth::user()->name);
        if(!empty($template)){
            $json = "<b>$nome</b> você tem certeza que deseja excluir este Template?";
            return response()->json(['error' => $json,'id' => $template->id]);
        }else{
            return response()->json(['success' => true]);
        }
    }
    
    public function deleteon(Request $request)
    { 
        $template = Template::where('id', $request->template_id)->first();  
        $templateR = $template->titulo;
        if(!empty($template)){
            Storage::delete($template->imagem);
            Cropper::flush($template->imagem);
            $template->delete();
        }
        return Redirect::route('templates.index')->with([
            'color' => 'success', 
            'message' => 'O template '.$templateR.' foi removido com sucesso!'
        ]);
    }
}