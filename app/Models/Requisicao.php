<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    use HasFactory;

    protected $fillable = [
        'fornecedor_id',
        'setor_id',
        'unidade_id',
        'user_moderador_id',
        'user_liberador_id',
        'user_criacao_id',
        'user_alteracao_id',
        'simples_cotacao',
        'motivo_pedido_compra',
        'justificativa',
        'subtotal_pedido',
        'subtotal_entregue',
        'frete',
        'outras_despesas',
        'desconto',
        'acrescimo',
        'total_pedido',
        'total_entregue',
        'qtd_itens_pedido',
        'qtd_itens_entregue',
        'data_previa_conclusao',
        'aceito_pelo_fornecedor',
        'data_manifestacao_fornecedor',
        'integrado',
        'data_integracao',
        'status',
        'status_canc_devol',
        'mensagem',
        'fornecedor_email',
        'fornecedor_whatsapp',
        'dt_hr_envio_email_fornecedor',
        'justificativa_cancelamento',
        'sem_validacao',
        'portador',
    ];

    public function fornecedor(){
        return $this->belongsTo(Fornecedor::class);
    }

    public function unidade(){
        return $this->belongsTo(Unidade::class);
    }

    public function setor(){
        return $this->belongsTo(Setor::class);
    }

    public function moderador(){
        return $this->belongsTo(User::class,'user_moderador_id','id');
    }

    public function liberador(){
        return $this->belongsTo(User::class,'user_liberador_id','id');
    }

    public function criador(){
        return $this->belongsTo(User::class,'user_criacao_id','id');
    }

    public function itens(){
        return $this->hasMany(RequisicaoItem::class);
    }

    public function anexos(){
        return $this->hasMany(RequisicaoAnexo::class);
    }

    public function financeiros(){
        return $this->hasMany(Financeiro::class);
    }

    public function historicos(){
        return $this->hasMany(Historico::class);
    }

    public function qrcode(){
        return Qrcode::where('requisicao_id', $this->id)->first();
    }
}
