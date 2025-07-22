<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;
use App\Models\EntregaDevolucao;
use App\Models\Financeiro;
use App\Models\Operacao;
use App\Models\Alerta;
use chillerlan\QRCode\{QRCode, QROptions};

class CompraController extends Controller
{
    public function index(){
        $user = auth()->user();
        if($user->perfil->administrador || $user->perfil->confirmar_recebimento){
            $requisicoes = Requisicao::whereIn('status', ['Compra Aprovada'])->get();
        }
        elseif($user->perfil->moderar){
            $requisicoes = Requisicao::whereIn('status', ['Compra Aprovada'])
            ->where('user_moderador_id', $user->id)
            ->get();
        }
        return view('compras/index', compact('requisicoes','user'));
    }

    public function acessar($id){
        $user= auth()->user();
        $requisicao = Requisicao::where('id', $id)->first();
        $link   = route('acesso_fornecedor', $requisicao->qrcode()->link);
        $qrcode = (new QRCode)->render($link);

        Alerta::where('user_id', $user->id)
        ->where('origem','compra')
        ->where('requisicao_id',$requisicao->id)
        ->update(['visualizacao' => 'Sim']);

        $view_tipo_pagamento = [
            'Pagamento Antecipado' => 'Antecipado',
            'Pagamento Pós Entrega' => 'Avista',
            'Pagamento Data Vencimento' => 'A Prazo',
        ];

        return view('compras/acessar', compact('requisicao','qrcode','link','view_tipo_pagamento'));
    }

    public function entregas($id){
        $requisicao = Requisicao::where('id', $id)->first();
        return view('compras/entregas', compact('requisicao'));
    }

    public function entregas_set(Request $request){
        $user = auth()->user();
        $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
        if($user->perfil->administrador || $user->perfil->confirmar_recebimento){
            try {
                if($request->tipo_entrega == "total"){
                    foreach($requisicao->itens as $item){
                        if($item->qtd_entregue < ($item->qtd_pedida - $item->qtd_devolucao)){
                            $quantidade = $item->qtd_pedida - $item->qtd_entregue - $item->qtd_devolucao;
                            $item->qtd_entregue = $item->qtd_pedida - $item->qtd_devolucao;
                            $item->save();

                            $requisicao->qtd_itens_entregue += $quantidade;

                            $dados = [
                                'requisicao_item_id' => $item->id,
                                'user_criacao_id' => $user->id,
                                'entrega_devolucao' => 'E',
                                'qtd' => $quantidade,
                                'justificativa' => 'Entrega Total',
                            ];
                            EntregaDevolucao::create($dados);
                        }
                    }

                    //vamos setar a entrega total no historico
                    $ds_historico = "Entrega total do pedido";
                    set_historico($requisicao->id, $ds_historico, $requisicao->status);

                    $requisicao->save();

                    $this->testaStatusRequisicao($requisicao);

                    //vamos buscar os financeiros que são avista e a prazo
                    foreach($requisicao->financeiros as $financeiro){
                        if(!$financeiro->sisagil_id_retorno && $financeiro->tipo_pagamento == "Pagamento Pós Entrega" || $financeiro->tipo_pagamento == "Pagamento Data Vencimento"){
                            if($financeiro->tipo_pagamento == "Pagamento Pós Entrega"){
                                $financeiro->vencimento = date('Y-m-d');
                                $financeiro->save();
                            }
                            $api = new ApiSisAgilController($requisicao->unidade->token_sisagil);
                            $api->integra_financeiro($financeiro);
                        }
                    }

                    return redirect()->route('compras.entregas', $requisicao->id)->with('mensagem', 'Entrega Total Realizada');
                }
                elseif($request->tipo_entrega == "parcial"){
                    foreach($requisicao->itens as $item){
                        $var = "qtd_entregue_".$item->id;
                        $qt_entregue = $request->$var;

                        if($qt_entregue > 0){
                            $item->qtd_entregue += $qt_entregue;
                            $item->save();

                            $dados = [
                                'requisicao_item_id' => $item->id,
                                'user_criacao_id' => $user->id,
                                'entrega_devolucao' => 'E',
                                'qtd' => $qt_entregue,
                                'justificativa' => $request->justificativa,
                            ];
                            EntregaDevolucao::create($dados);

                            $requisicao->qtd_itens_entregue += $qt_entregue;
                        }
                    }

                    $requisicao->save();

                    //vamos setar a entrega total no historico
                    $ds_historico = "Entrega parcial do pedido";
                    set_historico($requisicao->id, $ds_historico, $requisicao->status);

                    $this->testaStatusRequisicao($requisicao);

                    //vamos buscar os financeiros que são avista e a prazo
                    foreach($requisicao->financeiros as $financeiro){
                        if(!$financeiro->sisagil_id_retorno && $financeiro->tipo_pagamento == "Pagamento Pós Entrega" || $financeiro->tipo_pagamento == "Pagamento Data Vencimento"){
                            if($financeiro->tipo_pagamento == "Pagamento Pós Entrega"){
                                $financeiro->vencimento = date('Y-m-d');
                                $financeiro->save();
                            }
                            $api = new ApiSisAgilController($requisicao->unidade->token_sisagil);
                            $api->integra_financeiro($financeiro);
                        }
                    }

                    return redirect()->route('compras.entregas', $requisicao->id)->with('mensagem', 'Entrega Parcial Realizada');
                }
                elseif($request->tipo_entrega == "cancelamento"){
                    $valor_cancelamento = 0;
                    foreach($requisicao->itens as $item){
                        $var = "qtd_cancelamento_".$item->id;
                        $qt_cancelamento = $request->$var;

                        if($qt_cancelamento > 0){
                            $item->qtd_devolucao += $qt_cancelamento;
                            $item->save();

                            $dados = [
                                'requisicao_item_id' => $item->id,
                                'user_criacao_id' => $user->id,
                                'entrega_devolucao' => 'D',
                                'qtd' => $qt_cancelamento,
                                'justificativa' => $request->justificativa,
                            ];
                            EntregaDevolucao::create($dados);

                            $valor_cancelamento += $qt_cancelamento * $item->valor_unid;
                        }
                    }

                    if($valor_cancelamento > 0){
                        //vamos buscar a conta padrao de credito nessa unidade
                        $conta_pagamento = $requisicao->unidade->get_conta_padrao('C') ;
                        $conta_pagamento_id = $conta_pagamento ? $conta_pagamento->id : $item->conta_pagamento_id;
                        //vamos buscar a operacao de devolução padrao
                        $operacao = Operacao::get_operacao_padrao_cancelamento();
                        $operacao_id = $operacao ? $operacao->id : $requisicao->financeiros()->first()->operacao_id;

                        $user = auth()->user();
                        $dados = [
                            'requisicao_id' => $requisicao->id,
                            'fornecedor_id' => $requisicao->fornecedor_id,
                            'operacao_id' => $operacao_id,
                            'conta_pagamento_id' => $conta_pagamento_id,
                            'user_criacao_id' => $user->id,
                            'cred_deb' => 'Crédito',
                            'tipo_pagamento' => 'Pagamento Data Vencimento',
                            'origem' => 'Devolução/Cancelamento',
                            'descricao' => 'Devolução/Cancelamento de itens',
                            'vencimento' => date('Y-m-d'),
                            'valor' => $valor_cancelamento,
                        ];

                        $financeiro = Financeiro::create($dados);
                        $api = new ApiSisAgilController($requisicao->unidade->token_sisagil);
                        $api->integra_financeiro($financeiro);
                    }

                    //vamos setar a entrega total no historico
                    $ds_historico = "Cancelamento/Devolução de itens do pedido.";
                    set_historico($requisicao->id, $ds_historico, $requisicao->status);

                    $this->testaStatusRequisicao($requisicao);
                    return redirect()->route('compras.entregas', $requisicao->id)->with('mensagem', 'Cancelamento/Devolução Cadastrado!');
                }
            }catch(\Exception $e) {
                return redirect()->route('compras.entregas', $requisicao->id)->with('mensagem_erro', $e->getMessage());
            }
        }
        else{
            return redirect()->route('compras.entregas', $requisicao->id)->with('mensagem_erro', 'Você não possui este acesso!');
        }
    }

    public function testaStatusRequisicao($requisicao){
        $controle = true;
        //vamos verificar todos os itens
        foreach($requisicao->itens as $item){
            if($item->qtd_pedida > ($item->qtd_entregue + $item->qtd_devolucao)){
                $controle = false;
            }
        }

        //vamos verificar se todos os financeiros foram integrados
        foreach($requisicao->financeiros as $financeiro){
            if(!$financeiro->sisagil_id_retorno){
                $controle = false;
            }
        }

        if($controle){
            $requisicao->status = "Compra Finalizada";
            $requisicao->save();

            //vamos setar a entrega total no historico
            $ds_historico = "Compra Finalizada";
            set_historico($requisicao->id, $ds_historico, $requisicao->status);
        }
    }

    public function cancelar($id){
        $user = auth()->user();
        if($user->perfil->administrador || $user->perfil->cancelar){
            $requisicao = Requisicao::where('id', $id)->first();
            return view('compras/cancelar', compact('requisicao'));
        }
    }

    public function cancelar_set(Request $request){
        $user = auth()->user();
        if($user->perfil->administrador || $user->perfil->cancelar){
            try {
                $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
                $requisicao->status = 'Compra Cancelada';
                $requisicao->save();

                return redirect()->route('compras')->with('mensagem', 'Compra Cancelada');
            } catch (\Exception $e) {
                return redirect()->route('compras')->with('mensagem_erro', $e->getMessage());
            }
        }
    }

    public function imprimir($id){
        $requisicao = Requisicao::where('id', $id)->first();
        $link   = route('acesso_fornecedor', $requisicao->qrcode()->link);
        $qrcode = (new QRCode)->render($link);
        return view('imprimir/index', compact('requisicao','link','qrcode'));
    }

    public function integrar($id){
        $user = auth()->user();
        $requisicao = Requisicao::where('id', $id)->first();
        if($user->perfil->administrador || $user->id == $requisicao->user_moderador_id){
            $view_tipo_pagamento = [
                'Pagamento Antecipado' => 'Antecipado',
                'Pagamento Pós Entrega' => 'Avista',
                'Pagamento Data Vencimento' => 'A Prazo',
            ];
            return view('compras/integrar', compact('requisicao','view_tipo_pagamento'));
        }
        else{
            return redirect()->route('compras')->with('mensagem_erro','Você não possui este acesso');
        }
    }

    public function integrar_set(Request $request){
        try {
            if($request->tipo_integracao == "completa"){
                $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
                $api = new ApiSisAgilController($requisicao->unidade->token_sisagil);
                foreach($requisicao->financeiros as $financeiro){
                    if(!$financeiro->sisagil_id_retorno){
                        $api->integra_financeiro($financeiro);
                    }
                }
            }
            elseif($request->tipo_integracao == "parcela"){
                $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
                $api = new ApiSisAgilController($requisicao->unidade->token_sisagil);
                $financeiro = Financeiro::where('id', $request->financeiro_id)->first();
                if(!$financeiro->sisagil_id_retorno){
                    $api->integra_financeiro($financeiro);
                }
            }

            return redirect()->route('compras.integrar', $request->requisicao_id)->with('mensagem','Integração Realizada');
        } catch (\Exception $e) {
            return redirect()->route('compras')->with('mensagem_erro',$e->getMessage());
        }
    }

    public function get_parcela_sisagil($id){
        $financeiro = Financeiro::where('id', $id)->first();
        $requisicao = Requisicao::where('id', $financeiro->requisicao_id)->first();
        $api = new ApiSisAgilController($requisicao->unidade->token_sisagil);
        $api->get_parcela_sisagil($financeiro->id);
    }

}
