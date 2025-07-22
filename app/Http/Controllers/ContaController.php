<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContaPagamento;
use App\Models\Unidade;

class ContaController extends Controller
{
    public function index(){
        $contas = ContaPagamento::all();
        return view('conta_pagamentos/index', compact('contas'));
    }

    public function adicionar(){
        $unidades = Unidade::all()->sortBy('nome');
        return view('conta_pagamentos/adicionar', compact('unidades'));
    }

    public function insert(Request $request){
        try {
            $dados = $request->except('_token','padrao');
            $dados['padrao'] = $request->padrao == "S" ? true : false;

            if($dados['padrao']){
                $dados_where = [
                    'unidade_id' => $dados['unidade_id'],
                    'cred_deb' => $dados['cred_deb'],
                ];
                ContaPagamento::where($dados_where)->update(['padrao' => false]);
            }

            ContaPagamento::create($dados);

            return redirect()->route('contas')->with('mensagem', 'Conta Pagamento Cadastrada!');
        } catch (\Exception $e) {
            return redirect()->route('contas')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function editar($id){
        $conta = ContaPagamento::where('id', $id)->first();
        $unidades = Unidade::all()->sortBy('nome');
        return view('conta_pagamentos/editar', compact('conta','unidades'));
    }

    public function update(Request $request){
        try {
            $dados = $request->except('_token','conta_pagamento_id','padrao');
            $dados['padrao'] = $request->padrao == "S" ? true : false;

            if($dados['padrao']){
                $dados_where = [
                    'unidade_id' => $dados['unidade_id'],
                    'cred_deb' => $dados['cred_deb'],
                ];
                ContaPagamento::where($dados_where)->update(['padrao' => false]);
            }
            ContaPagamento::where('id', $request->conta_pagamento_id)->update($dados);

            return redirect()->route('contas')->with('mensagem', 'Conta Pagamento Editado!');
        } catch (\Exception $e) {
            return redirect()->route('contas')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function excluir($id){
        $conta = ContaPagamento::where('id', $id)->first();
        return view('conta_pagamentos/excluir', compact('conta'));
    }

    public function delete(Request $request){
        try {
            ContaPagamento::where('id', $request->conta_pagamento_id)->delete();

            return redirect()->route('contas')->with('mensagem', 'Conta Pagamento Excluída!');
        } catch (\Exception $e) {
            return redirect()->route('contas')->with('mensagem_erro', $e->getMessage());
        }
    }
}
