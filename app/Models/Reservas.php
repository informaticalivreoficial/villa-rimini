<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservas extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'cliente',        
        'apartamento',
        'status',
        'adultos',
        'criancas_0_5',
        'checkin',
        'checkout',
        'codigo',
        'valor_venda',
        'notasadicionais',
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
    public function user()
    {
        return $this->belongsTo(User::class, 'cliente', 'id');
    }

    public function userObject()
    {
        return $this->hasOne(User::class, 'id', 'cliente');
    }

    public function apartamento()
    {
        return $this->belongsTo(Apartamento::class, 'apartamento', 'id');
    }

    public function apartamentoObject()
    {
        return $this->hasOne(Apartamento::class, 'id', 'apartamento');
    }

    /**
     * Accerssors and Mutators
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }

    public function setValorVendaAttribute($value)
    {
        $this->attributes['valor_venda'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    }

    public function getValorVendaAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return number_format($value, 2, ',', '.');
    }

    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return date('d/m/Y', strtotime($value));
    }

    public function setCheckinAttribute($value)
    {
        $this->attributes['checkin'] = (!empty($value) ? $this->convertStringToDate($value) : null);
    }
    
    public function getCheckinAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return date('d/m/Y', strtotime($value));
    }

    public function setCheckoutAttribute($value)
    {
        $this->attributes['checkout'] = (!empty($value) ? $this->convertStringToDate($value) : null);
    }
    
    public function getCheckoutAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return date('d/m/Y', strtotime($value));
    }

    private function convertStringToDate(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }
}
