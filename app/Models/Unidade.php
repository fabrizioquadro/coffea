<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cod_sisagil',
        'token_sisagil',
        'usuario_sisagil',
        'senha_sisagil',
        'status',
        'logo',
        'restrita',
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function get_conta_padrao($cred_deb){
        return ContaPagamento::where('unidade_id', $this->id)
        ->where('cred_deb', $cred_deb)
        ->where('padrao', true)
        ->first();
    }
}
