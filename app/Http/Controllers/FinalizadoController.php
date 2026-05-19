<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;
use chillerlan\QRCode\{QRCode, QROptions};

class FinalizadoController extends Controller
{
    public function index(){
        $user = auth()->user();
        if($user->perfil->administrador || $user->perfil->confirmar_recebimento){
            $requisicoes = Requisicao::whereIn('status', ['Compra Finalizada'])->get();
        }
        elseif($user->perfil->moderar){
            $requisicoes = Requisicao::whereIn('status', ['Compra Finalizada'])
            ->where('user_moderador_id', $user->id)
            ->get();
        }
        return view('finalizados/index', compact('requisicoes','user'));
    }

    public function acessar($id){
        $requisicao = Requisicao::where('id', $id)->first();
        $link   = route('acesso_fornecedor', $requisicao->qrcode()->link);
        $qrcode = (new QRCode)->render($link);

        $view_tipo_pagamento = [
            'Pagamento Antecipado' => 'Antecipado',
            'Pagamento Pós Entrega' => 'Avista',
            'Pagamento Data Vencimento' => 'A Prazo',
        ];

        return view('finalizados/acessar', compact('requisicao','qrcode','link','view_tipo_pagamento'));
    }

    public function entregas($id){
        $requisicao = Requisicao::where('id', $id)->first();
        return view('finalizados/entregas', compact('requisicao'));
    }

    public function integrar($id){
        $user = auth()->user();
        $requisicao = Requisicao::where('id', $id)->first();
        $view_tipo_pagamento = [
            'Pagamento Antecipado' => 'Antecipado',
            'Pagamento Pós Entrega' => 'Avista',
            'Pagamento Data Vencimento' => 'A Prazo',
        ];
        return view('finalizados/integrar', compact('requisicao','view_tipo_pagamento'));
    }
}
