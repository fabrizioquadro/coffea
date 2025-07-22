<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financeiro extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisicao_id',
        'fornecedor_id',
        'operacao_id',
        'conta_pagamento_id',
        'user_criacao_id',
        'user_alteracao_id',
        'cred_deb',
        'tipo_pagamento',
        'origem',
        'descricao',
        'vencimento',
        'valor',
        'doc',
        'obs',
        'sisagil_id_retorno',
    ];

    public function operacao(){
        return $this->belongsTo(Operacao::class);
    }

    public function conta_pagamento(){
        return $this->belongsTo(ContaPagamento::class);
    }

    public function requisicao(){
        return $this->belongsTo(Requisicao::class);
    }

    public function fornecedor(){
        return $this->belongsTo(Fornecedor::class);
    }
}
