<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisicaoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisicao_id',
        'item_id',
        'user_criacao_id',
        'user_alteracao_id',
        'obs',
        'ds_unidade',
        'valor_unid',
        'qtd_pedida',
        'data_previsao_entrega',
        'qtd_entregue',
        'qtd_devolucao',
        'qtd_total',
        'valor_total_pedido',
        'valor_total_entregue',
        'status',
        'status_canc_devol',
        'lancar_patrimonio',
    ];

    public function item(){
        return $this->belongsTo(Item::class);
    }
}
