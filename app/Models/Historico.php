<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisicao_id',
        'user_id',
        'dt_historico',
        'ds_historico',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
