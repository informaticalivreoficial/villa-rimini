<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Apartamento extends Model
{
    use HasFactory;

    protected $table = 'apartamentos'; 
    
    protected $fillable = [
        //SEO
        'titulo', 'slug', 'status', 'views', 'metatags', 'headline',
        'descricao',
        'notasadicionais',
        'dormitorios',
        'views',        
        'exibirmarcadagua',
        'youtube_video',  
        'exibir_valores',
        'exibir_home',
        'valor_cafe',
        'valor_cafe_almoco',
        'valor_cafe_janta',
        'valor_cri_0_5',

        //ACESSORIOS
        'ar_condicionado',
        'cafe_manha',
        'cofre_individual',
        'frigobar',
        'servico_quarto',
        'telefone',
        'estacionamento',
        'espaco_fitness',
        'lareira',
        'elevador',
        'vista_para_mar',
        'ventilador_teto',
        'wifi',
    ];

    /**
     * Scopes
    */

    public function scopeAvailable($query)
    {
        return $query->where('status', 1);
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Relacionamentos
    */    
    public function reservas()
    {
        return $this->hasMany(Reservas::class, 'apartamento', 'id');
    }

    public function images()
    {
        return $this->hasMany(ApartamentoGb::class, 'apartamento', 'id')->orderBy('cover', 'ASC');
    }
    
    public function countimages()
    {
        return $this->hasMany(ApartamentoGb::class, 'apartamento', 'id')->count();
    }

    public function imagesmarcadagua()
    {
        return $this->hasMany(ApartamentoGb::class, 'apartamento', 'id')->whereNull('marcadagua')->count();
    }

    /**
     * Accerssors and Mutators
     */
    public function getContentWebAttribute()
    {
        return Str::words($this->descricao, '20', ' ...');
    }

    public function cover()
    {
        $images = $this->images();
        $cover = $images->where('cover', 1)->first(['path']);

        if(!$cover) {
            $images = $this->images();
            $cover = $images->first(['path']);
        }

        if(empty($cover['path']) || !Storage::disk()->exists($cover['path'])) {
            return url(asset('backend/assets/images/image.jpg'));
        }

        return Storage::url($cover['path']);
    }

    public function setExibirValoresAttribute($value)
    {
        $this->attributes['exibir_valores'] = ($value == true || $value == '1' ? 1 : 0);
    }

    public function setExibirHomeAttribute($value)
    {
        $this->attributes['exibir_home'] = ($value == true || $value == '1' ? 1 : 0);
    }

    public function setExibirmarcadaguaAttribute($value)
    {
        $this->attributes['exibirmarcadagua'] = ($value == true || $value == '1' ? 1 : 0);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }

    public function setValorCafeAttribute($value)
    {
        $this->attributes['valor_cafe'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    }

    public function getValorCafeAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return number_format($value, 2, ',', '.');
    }

    public function setValorCafeAlmocoAttribute($value)
    {
        $this->attributes['valor_cafe_almoco'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    }

    public function getValorCafeAlmocoAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return number_format($value, 2, ',', '.');
    }

    public function setValorCafeJantaAttribute($value)
    {
        $this->attributes['valor_cafe_janta'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    }

    public function getValorCafeJantaAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return number_format($value, 2, ',', '.');
    }

    public function setValorCri05Attribute($value)
    {
        $this->attributes['valor_cri_0_5'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    }

    public function getValorCri05Attribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return number_format($value, 2, ',', '.');
    }

    /**
     * Mutator Estacionamento
     *
     * @param $value
     */
    public function setEstacionamentoAttribute($value)
    {
        $this->attributes['estacionamento'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Telefone
     *
     * @param $value
     */
    public function setTelefoneAttribute($value)
    {
        $this->attributes['telefone'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Café da manhã
     *
     * @param $value
     */
    public function setCafeManhaAttribute($value)
    {
        $this->attributes['cafe_manha'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Ar Condicionado
     *
     * @param $value
     */
    public function setArCondicionadoAttribute($value)
    {
        $this->attributes['ar_condicionado'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Cofre Individual
     *
     * @param $value
     */
    public function setCofreIndividualAttribute($value)
    {
        $this->attributes['cofre_individual'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Frigobar
     *
     * @param $value
     */
    public function setFrigobarAttribute($value)
    {
        $this->attributes['frigobar'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Lareira
     *
     * @param $value
     */
    public function setLareiraAttribute($value)
    {
        $this->attributes['lareira'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Elevador
     *
     * @param $value
     */
    public function setElevadorAttribute($value)
    {
        $this->attributes['elevador'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Vista para o Mar
     *
     * @param $value
     */
    public function setVistaParaMarAttribute($value)
    {
        $this->attributes['vista_para_mar'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Ventilador de Teto
     *
     * @param $value
     */
    public function setVentiladorTetoAttribute($value)
    {
        $this->attributes['ventilador_teto'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Espaço Fitness
     *
     * @param $value
     */
    public function setEspacofitnessAttribute($value)
    {
        $this->attributes['espaco_fitness'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Serviço de Quarto
     *
     * @param $value
     */
    public function setServicoQuartoAttribute($value)
    {
        $this->attributes['servico_quarto'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    /**
     * Mutator Wifi
     *
     * @param $value
     */
    public function setWifiAttribute($value)
    {
        $this->attributes['wifi'] = (($value === true || $value === 'on') ? 1 : 0);
    }

    public function setSlug()
    {
        if(!empty($this->titulo)){
            $post = Apartamento::where('titulo', $this->titulo)->first(); 
            if(!empty($post) && $post->id != $this->id){
                $this->attributes['slug'] = Str::slug($this->titulo) . '-' . $this->id;
            }else{
                $this->attributes['slug'] = Str::slug($this->titulo);
            }            
            $this->save();
        }
    }

    private function convertStringToDouble($param)
    {
        if(empty($param)){
            return null;
        }
        return str_replace(',', '.', str_replace('.', '', $param));
    }

    private function clearField(?string $param)
    {
        if(empty($param)){
            return null;
        }
        return str_replace(['.', '-', '/', '(', ')', ' '], '', $param);
    }
}
