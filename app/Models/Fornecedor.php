<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'sisagil_id',
        'nome',
        'fantasia',
        'cpf_cnpj',
        'endereco',
        'numero',
        'bairro',
        'complemento',
        'cidade',
        'uf',
        'celular',
        'enviar_whatsapp',
        'email',
        'cep',
        'unidade_id',
        'status',
    ];
}
