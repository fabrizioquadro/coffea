<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisicaoAnexo extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisicao_id',
        'fornecedor_id',
        'user_criacao_id',
        'user_alteracao_id',
        'link_anexo',
    ];

    public function fornecedor(){
        return $this->belongsTo(Fornecedor::class);
    }
}
