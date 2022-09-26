<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\Admin\AdminSend;
use App\Mail\Admin\SuporteSend;
use App\Models\Empresa;
use App\Models\User;

class EmailController extends Controller
{    

    public function send(Request $request)
    {
        $user = auth()->user();
        $destinatario = $request->except('_token');        
        
        return view('admin.email.send', [
            'user' => $user,
            'destinatario' => $destinatario
        ]);        
    }    
    
    
    public function sendEmail(Request $request)
    {
        if($request->assunto == ''){
            return redirect()->back()->with([
                'color' => 'danger', 
                'message' => 'Erro, por favor preencha o campo assunto!'
            ]);
        }
        if($request->destinatario_email == ''){
            return redirect()->back()->with([
                'color' => 'danger', 
                'message' => 'Erro, por favor informe um destinatário para o envio!'
            ]);
        }

        $data['reply_name'] = $request->remetente_nome;        
        $data['reply_email'] = $request->remetente_email;        
        $data['destinatario_email'] = $request->destinatario_email; 
        if($request->destinatario_nome != ''){
            $data['destinatario_nome'] = $request->destinatario_nome;
        }
        if($request->copiapara != ''){
            $data['copiapara'] = $request->copiapara;
        }     
        if($request->allFiles()){
            $data['anexo'] = $request->allFiles()['anexos'];
        }     
        $data['sitename'] = $request->sitename;        
        // $data['anexo'] = $request->file('anexo');        
        $data['assunto'] = $request->assunto;        
        $data['mensagem'] = $request->mensagem;  

        Mail::send(new AdminSend($data));
        return redirect()->route('email.success')->with([
            'color' => 'success', 
            'message' => 'Email enviado com sucesso!'
        ]);
    }

    public function success()
    {
        return view('admin.email.success');
    }

    public function suporte(Request $request)
    {
        if($request->mensagem == ''){
            $json = $this->message->error('Erro, por favor preencha o campo mensagem!')->render();
            return response()->json(['error' => $json]);
        }else{
            $data = [
                'username' => $request->username, 
                'email' => $request->email,
                'sitename' => $request->sitename,
                'mensagem' => $request->mensagem
            ];
            Mail::send(new SuporteSend($data));
            $json = $this->message->error('Obrigado, sua solicitação de suporte foi enviada com sucesso!')->render();
            return response()->json(['success' => $json]);            
        }
    }
    
}
