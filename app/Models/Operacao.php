<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'sisagil_id',
        'descricao',
        'status',
        'operacao_padrao_cancelamento',
    ];

    public static function get_operacao_padrao_cancelamento(){
        return SELF::where('operacao_padrao_cancelamento','Sim')->first();
    }
}
