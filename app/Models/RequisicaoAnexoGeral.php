<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisicaoAnexoGeral extends Model
{
    protected $table = 'requisicao_anexos_gerais';

    protected $fillable = [
        'requisicao_id',
        'user_criacao_id',
        'link_anexo',
    ];
}
