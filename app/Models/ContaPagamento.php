<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaPagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'sisagil_id',
        'unidade_id',
        'descricao',
        'cred_deb',
        'padrao',
    ];

    public function unidade(){
        return $this->belongsTo(Unidade::class);
    }
}
