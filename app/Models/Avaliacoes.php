<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Support\Cropper;

class Avaliacoes extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'name',
        'email',
        'questao_1',
        'questao_2',
        'questao_3',
        'questao_4',
        'questao_5',
        'questao_6',
        'questao_7',
        'questao_7_content',
        'questao_8',
        'questao_9',
        'questao_10',
        'questao_11',
        'content',
        'status'
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
     * Accerssors and Mutators
     */
    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return date('d/m/Y', strtotime($value));
    }
}
