<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisicao_id',
        'user_criacao_id',
        'user_ativacao_id',
        'vencimento',
        'verificador',
        'ativacao',
    ];
}
