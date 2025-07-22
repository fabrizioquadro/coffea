<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntregaDevolucao extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisicao_item_id',
        'user_criacao_id',
        'entrega_devolucao',
        'qtd',
        'justificativa',
    ];
}
