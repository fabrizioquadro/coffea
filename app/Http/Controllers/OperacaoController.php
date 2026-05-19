<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operacao;
use App\Models\Unidade;

class OperacaoController extends Controller
{
    public function index(){
        $operacoes = Operacao::all();
        return view('operacoes/index', compact('operacoes'));
    }

    public function adicionar(){
        return view('operacoes/adicionar');
    }

    public function insert(Request $request){
        try {
            if($request->operacao_padrao_cancelamento == 'Sim'){
                Operacao::where('operacao_padrao_cancelamento', 'Sim')->update(['operacao_padrao_cancelamento' => 'Não']);
            }
            $dados = $request->except('_token');
            Operacao::create($dados);

            return redirect()->route('operacoes')->with('mensagem', 'Operação Cadastrada!');
        } catch (\Exception $e) {
            return redirect()->route('operacoes')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function editar($id){
        $operacao = Operacao::where('id', $id)->first();
        return view('operacoes/editar', compact('operacao'));
    }

    public function update(Request $request){
        try {
            if($request->operacao_padrao_cancelamento == 'Sim'){
                Operacao::where('operacao_padrao_cancelamento', 'Sim')->update(['operacao_padrao_cancelamento' => 'Não']);
            }
            $dados = $request->except('_token','operacao_id');
            Operacao::where('id', $request->operacao_id)->update($dados);

            return redirect()->route('operacoes')->with('mensagem', 'Operação Editada!');
        } catch (\Exception $e) {
            return redirect()->route('operacoes')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function excluir($id){
        $operacao = Operacao::where('id', $id)->first();
        return view('operacoes/excluir', compact('operacao'));
    }

    public function delete(Request $request){
        try {
            Operacao::where('id', $request->operacao_id)->delete();

            return redirect()->route('operacoes')->with('mensagem', 'Operação Excluída!');
        } catch (\Exception $e) {
            return redirect()->route('operacoes')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function sincronizar_sisagil(){
        $unidade_geral = Unidade::where('restrita','Não')->first();
        $unidades_restritas = Unidade::where('restrita','Sim')->get();

        //vamos começar pelas unidades gerais
        $api_sisagil = new ApiSisAgilController($unidade_geral->token_sisagil);
        $operacoes = $api_sisagil->get_operacao_sisagil();


        foreach($operacoes as $oper){
            $dados = [
                'descricao' => $oper['descricao'],
                'unidade_id' => null,
            ];

            $operacao = Operacao::where('sisagil_id', $oper['id'])->first();
            if($operacao){
                Operacao::where('id', $operacao->id)->update($dados);
            }
            else{
                $dados['sisagil_id'] = $oper['id'];
                $dados['status'] = 'Ativo';
                Operacao::create($dados);
            }
        }

        foreach($unidades_restritas as $unidade){
            //echo "$unidade->nome <br>";
            $api_sisagil = new ApiSisAgilController($unidade->token_sisagil);
            $operacoes = $api_sisagil->get_operacao_sisagil();
            //echo "<pre>";
            //print_r($operacoes);
            //echo "</pre>";

            foreach($operacoes as $oper){
                $dados = [
                    'descricao' => $oper['descricao'],
                    'unidade_id' => $unidade->id,
                ];

                $operacao = Operacao::where('sisagil_id', $oper['id'])
                ->where('unidade_id',$unidade->id)
                ->first();
                if($operacao){
                    Operacao::where('id', $operacao->id)->update($dados);
                }
                else{
                    $dados['sisagil_id'] = $oper['id'];
                    $dados['status'] = 'Ativo';
                    Operacao::create($dados);
                }
            }
        }

        return redirect()->route('operacoes')->with('mensagem', 'Sincronização efetuada!');
    }
}
