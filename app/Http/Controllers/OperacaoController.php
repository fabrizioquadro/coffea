<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operacao;

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
}
