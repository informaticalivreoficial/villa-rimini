<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Support\Cropper;

class Configuracoes extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'ano_de_inicio',
        'nomedosite',
        'politicas_de_privacidade',
        'cnpj',
        'ie',
        'dominio',
        'template',
        //Imagens
        'logomarca',
        'logomarca_admin',        
        'logomarca_footer',
        'favicon',        
        'metaimg',
        'imgheader',
        'marcadagua',
        //SMTP
        'smtp_host',
        'smtp_port',
        'smtp_user',
        'smtp_pass',
        //Contato
        'telefone1',
        'telefone2',
        'telefone3',
        'whatsapp',
        'skype',
        'email',
        'email1',
        //Endereço
        'cep',
        'rua',
        'num',
        'complemento',
        'bairro',
        'uf',
        'cidade',
        //Social links
        'facebook',
        'twitter',
        'youtube',
        'instagram',
        'linkedin',
        'vimeo',
        'fliccr',
        'soundclound',
        'snapchat',
        //Seo
        'descricao',
        'mapa_google',
        'metatags',
        'rss',
        'rss_data',
        'sitemap',
        'sitemap_data',
        'analytics_view',
        'tagmanager_id'
    ];

    /**
     * Accerssors and Mutators
     */
    
    public function getmetaimg()
    {
        if(empty($this->metaimg) || !Storage::disk()->exists($this->metaimg)) {
            return url(asset('backend/assets/images/image.jpg'));
        } 
        return Storage::url($this->metaimg);
    }
    
    public function getlogomarca()
    {
        if(empty($this->logomarca) || !Storage::disk()->exists($this->logomarca)) {
            return url(asset('backend/assets/images/image.jpg'));
        } 
        return Storage::url($this->logomarca);
    }
    
    public function getlogoadmin()
    {
        if(empty($this->logomarca_admin) || !Storage::disk()->exists($this->logomarca_admin)) {
            return url(asset('backend/assets/images/image.jpg'));
        } 
        return Storage::url($this->logomarca_admin);
    }
    
    public function getfaveicon()
    {
        if(empty($this->favicon) || !Storage::disk()->exists($this->favicon)) {
            return url(asset('backend/assets/images/image.jpg'));
        } 
        return Storage::url($this->favicon);
    }
    
    public function getmarcadagua()
    {
        if(empty($this->marcadagua) || !Storage::disk()->exists($this->marcadagua)) {
            return url(asset('backend/assets/images/image.jpg'));
        } 
        return Storage::url($this->marcadagua);
    }
    
    public function gettopodosite()
    {
        if(empty($this->imgheader) || !Storage::disk()->exists($this->imgheader)) {
            return url(asset('backend/assets/images/image.jpg'));
        } 
        return Storage::url($this->imgheader);
    }
    
    public function setCepAttribute($value)
    {
        $this->attributes['cep'] = (!empty($value) ? $this->clearField($value) : null);
    }
    
    public function getCepAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return substr($value, 0, 5) . '-' . substr($value, 5, 3);
    }
    
    public function setWhatsappAttribute($value)
    {
        $this->attributes['whatsapp'] = (!empty($value) ? $this->clearField($value) : null);
    }
    
    //Formata o celular para exibir
    public function getWhatsappAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return  
            substr($value, 0, 0) . '(' .
            substr($value, 0, 2) . ') ' .
            substr($value, 2, 5) . '-' .
            substr($value, 7, 4) ;
    }

    
    private function convertStringToDate(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }
    
    private function clearField(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        return str_replace(['.', '-', '/', '(', ')', ' '], '', $param);
    }
}
