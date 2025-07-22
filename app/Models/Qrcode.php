<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisicao_id',
        'link',
        'vencimento',
        'ip_ultima_leitura',
        'data_ultima_leitura',
        'tipo_validacao',
        'justificativa',
        'aceite_fornecedor',
        'manifestacao_fornecedor',
    ];
}
