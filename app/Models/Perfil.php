<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'administrador',
        'unidades',
        'setores',
        'perfil',
        'usuarios',
        'operacoes',
        'contas',
        'criar',
        'preparar_compra',
        'duplicar_pedido_compra',
        'moderar',
        'aprovar',
        'confirmar_recebimento',
        'alterar_qtd_recebimento',
        'editar',
        'corrigir',
        'cancelar',
        'acompanhar',
        'status',
        'user_id_cadastro',
        'user_id_alteracao',
        'moderar_todos',
        'aprovar_todos',
        'somente_solicitar_pedido',
        'integrar_financeiro',
        'finalizados',
    ];

    public function user_cad(){
        return $this->belongsTo(User::class,'user_id_cadastro','id');
    }

    public function user_update(){
        return $this->belongsTo(User::class,'user_id_alteracao','id');
    }
}
