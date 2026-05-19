<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;
use App\Models\Operacao;
use App\Models\Financeiro;
use App\Models\Unidade;
use App\Models\Fornecedor;

class RelatorioController extends Controller
{
    public function requisicao(){
        $unidades = Unidade::all()->sortBy('nome');

        return view('relatorios/requisicao/index', compact('unidades'));
    }

    public function requisicao_gerar(Request $request){
        $where = array();
        //vamos montar os filtros
        $filtro['fornecedor'] = null;
        $filtro['unidade'] = null;
        $filtro['dt_inc'] = null;
        $filtro['dt_fn'] = null;
        $filtro['status'] = null;

        if($request->fornecedor_id){
            $where[] = [
                'fornecedor_id',
                '=',
                $request->fornecedor_id,
            ];
            $filtro['fornecedor'] = Fornecedor::where('id', $request->fornecedor_id)->first()->nome;
        }

        if($request->unidade_id){
            $where[] = [
                'unidade_id',
                '=',
                $request->unidade_id,
            ];
            $filtro['unidade'] = Unidade::where('id', $request->unidade_id)->first()->nome;
        }

        if($request->dt_inc){
            $where[] = [
                'created_at',
                '>=',
                $request->dt_inc." 00:00:00",
            ];
            $filtro['dt_inc'] = dataDbForm($request->dt_inc);
        }

        if($request->dt_fn){
            $where[] = [
                'created_at',
                '<=',
                $request->dt_fn." 23:59:59",
            ];
            $filtro['dt_inc'] = dataDbForm($request->dt_fn);
        }

        $controle_filtro = false;
        $controle_opcao = false;

        if($request->status){
            $filtro['status'] = $request->status;
            if($request->status == "Compra Aprovada (entregue)"){
                $where[] = [
                    'status',
                    '=',
                    'Compra Aprovada',
                ];
                $controle_filtro = true;
                $controle_opcao = 'Entrega Total';
            }
            elseif($request->status == "Compra Aprovada (não entregue)"){
                $where[] = [
                    'status',
                    '=',
                    'Compra Aprovada',
                ];
                $controle_filtro = true;
            }
            else{
                $where[] = [
                    'status',
                    '=',
                    $request->status,
                ];
            }
        }

        $requisicoes = Requisicao::where($where)->get();
        $array_requisicoes = array();

        foreach($requisicoes as $requisicao){
            if($controle_filtro){
                $retorno = CompraController::get_st_entrega($requisicao);
                if($request->status == 'Compra Aprovada (entregue)' && $retorno == 'Entrega Total'){
                    $array_requisicoes[] = $requisicao;
                }
                elseif($request->status == 'Compra Aprovada (não entregue)' && $retorno != 'Entrega Total'){
                    $array_requisicoes[] = $requisicao;
                }
            }
            else{
                $array_requisicoes[] = $requisicao;
            }
        }

        $total_pedidos = 0;
        $user = auth()->user();
        return view('relatorios/requisicao/gerar', compact('array_requisicoes','total_pedidos','filtro','user'));
    }

    public function financeiro(){
        $operacaos = Operacao::all()->sortBy('descricao');
        $unidades = Unidade::all()->sortBy('nome');
        return view('relatorios/financeiro/index', compact('operacaos','unidades'));
    }

    public function financeiro_gerar(Request $request){
        $where = array();
        $filtro['operacao'] = null;
        $filtro['unidade'] = null;
        $filtro['cred_deb'] = null;
        $filtro['origem'] = null;
        $filtro['dt_inc'] = null;
        $filtro['dt_fn'] = null;
        $filtro['integrado'] = null;
        $filtro['status'] = null;

        if($request->operacao_id){
            $where[] = [
                'operacao_id',
                '=',
                $request->operacao_id,
            ];
            $filtro['operacao'] = Operacao::where('id', $request->operacao_id)->first()->descricao;
        }

        if($request->cred_deb){
            $filtro['cred_deb'] = $request->cred_deb;
            $where[] = [
                'cred_deb',
                '=',
                $request->cred_deb,
            ];
        }

        if($request->origem){
            $filtro['origem'] = $request->origem;
            $where[] = [
                'origem',
                '=',
                $request->origem,
            ];
        }

        if($request->dt_inc){
            $filtro['dt_inc'] = dataDbForm($request->dt_inc);
            $where[] = [
                'vencimento',
                '>=',
                $request->dt_inc,
            ];
        }

        if($request->dt_fn){
            $filtro['dt_fn'] = dataDbForm($request->dt_fn);
            $where[] = [
                'vencimento',
                '<=',
                $request->dt_fn,
            ];
        }

        if($request->integrado == 'Sim'){
            $filtro['integrado'] = 'Sim';
            $where[] = [
                'sisagil_id_retorno',
                '<>',
                null,
            ];
        }

        if($request->integrado == 'Não'){
            $filtro['integrado'] = 'Não';
            $where[] = [
                'sisagil_id_retorno',
                '=',
                null,
            ];
        }

        if($request->status){
            $filtro['status'] = $request->status;
            if($request->status == "Compra Aprovada (entregue)" || $request->status == "Compra Aprovada (não entregue)"){
                $status = 'Compra Aprovada';
            }
            else{
                $status = $request->status;
            }

            $requisicaos = Requisicao::where('status', $status)->get();

            $in = array();
            foreach($requisicaos as $requisicao){
                if($request->status == "Compra Aprovada (entregue)"){
                    $retorno = CompraController::get_st_entrega($requisicao);;
                    if($request->status == 'Compra Aprovada (entregue)'){
                        if($retorno == 'Entrega Total'){
                            $in[] = $requisicao->id;
                        }
                    }
                    elseif($request->status == 'Compra Aprovada (não entregue)'){
                        if($retorno != 'Entrega Total'){
                            $in[] = $requisicao->id;
                        }
                    }
                    else{
                        $in[] = $requisicao->id;
                    }
                }
            }
            if($request->unidade_id){
                $financeiros = Financeiro::where($where)
                ->whereHas('requisicao', function ($query) use ($request){
                    $query->where('unidade_id', $request->unidade_id);
                })
                ->whereIn('requisicao_id', $in)
                ->orderBy('vencimento')->get();
                $filtro['unidade'] = Unidade::where('id', $request->unidade_id)->first()->nome;
            }
            else{
                $financeiros = Financeiro::where($where)->whereIn('requisicao_id', $in)->orderBy('vencimento')->get();
            }
        }
        else{
            if($request->unidade_id){
                $financeiros = Financeiro::where($where)
                ->whereHas('requisicao', function ($query) use ($request){
                    $query->where('unidade_id', $request->unidade_id);
                })
                ->orderBy('vencimento')->get();
                $filtro['unidade'] = Unidade::where('id', $request->unidade_id)->first()->nome;
            }
            else{
                $financeiros = Financeiro::where($where)->orderBy('vencimento')->get();
            }
        }
        $total_valor = 0;
        $user = auth()->user();
        return view('relatorios/financeiro/gerar', compact('financeiros','filtro','total_valor','user'));
    }

    public function imprimir(Request $request){
        $dados = $request->dados;
        return view('relatorios/imprimir', compact('dados'));
    }

}
