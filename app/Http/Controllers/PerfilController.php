<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfil;

class PerfilController extends Controller
{
    public $array_opcoes_db = ['administrador','unidades','setores','perfil','usuarios','operacoes','contas','criar','preparar_compra','duplicar_pedido_compra','moderar','aprovar','confirmar_recebimento','alterar_qtd_recebimento','editar','corrigir','cancelar','acompanhar','moderar_todos','aprovar_todos','somente_solicitar_pedido','integrar_financeiro','finalizados'];

    public function index(){
        $perfis = Perfil::all();
        return view('perfis/index', compact('perfis'));
    }

    public function adicionar(){
        $opcoes = $this->array_opcoes_db;
        return view('perfis/adicionar', compact('opcoes'));
    }

    public function insert(Request $request){
        try{
            $user = auth()->user();
            $dados = [
                'descricao' => $request->descricao,
                'status' => $request->status,
                'user_id_cadastro' => $user->id,
            ];

            foreach($this->array_opcoes_db as $opt){
                $dados[$opt] = $request->$opt == "Sim" ? true : false;
            }
            //dd($dados);
            Perfil::create($dados);
            return redirect()->route('perfis')->with('mensagem','Perfil Cadastrado!');
        }catch(\Exception $e) {
            return redirect()->route('perfis')->with('mensagem_erro',$e->getMessage());
        }
    }

    public function editar($id){
        $perfil = Perfil::where('id', $id)->first();

        $opcoes = $this->array_opcoes_db;
        return view('perfis/editar', compact('perfil','opcoes'));
    }

    public function update(Request $request){
        try{
            $user = auth()->user();
            $dados = [
                'descricao' => $request->descricao,
                'status' => $request->status,
                'user_id_alteracao' => $user->id,
            ];

            foreach($this->array_opcoes_db as $opt){
                $dados[$opt] = $request->$opt == "Sim" ? true : false;
            }
            Perfil::where('id', $request->perfil_id)->update($dados);
            return redirect()->route('perfis')->with('mensagem','Perfil Editado!');
        }catch(\Exception $e) {
            return redirect()->route('perfis')->with('mensagem_erro',$e->getMessage());
        }
    }

    public function excluir($id){
        $perfil = Perfil::where('id', $id)->first();
        return view('perfis/excluir', compact('perfil'));
    }

    public function delete(Request $request){
        try {
            Perfil::where('id', $request->perfil_id)->delete();

            return redirect()->route('perfis')->with('mensagem','Perfil Excluído!');
        }catch(\Exception $e) {
            return redirect()->route('perfis')->with('mensagem_erro', $e->getMessage());
        }

    }
}
