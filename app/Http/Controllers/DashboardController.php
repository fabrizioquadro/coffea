<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;

class DashboardController extends Controller
{
    public function index(){
        $user = auth()->user();
        $preparar_compra_requisicoes = null;

        if($user->perfil->preparar_compra){
            $preparar_compra_requisicoes = Requisicao::whereIn('status', ['Pedido','Pedido Compra','Retornado para Compra'])->get();
        }

        $validacao_requisicoes = Requisicao::whereIn('status', ['Em Validação','Retornado para Validação'])->where('user_moderador_id', $user->id)->get();
        $autorizacao_requisicoes = Requisicao::whereIn('status', ['Em Autorização','Aguardando Token de Aprovação'])->where('user_liberador_id', $user->id)->get();

        $nr_pedidos = Requisicao::where('user_criacao_id', $user->id)->count();
        $nr_preparos = Requisicao::whereIn('status', ['Pedido','Pedido Compra','Retornado para Compra'])->where('user_criacao_id', $user->id)->count();
        $nr_validacao = Requisicao::whereIn('status', ['Em Validação','Retornado para Validação'])->where('user_criacao_id', $user->id)->count();
        $nr_autorizacao = Requisicao::whereIn('status', ['Em Autorização','Aguardando Token de Aprovação'])->where('user_criacao_id', $user->id)->count();
        $nr_aprovados = Requisicao::whereIn('status', ['Compra Aprovada'])->where('user_criacao_id', $user->id)->count();
        $nr_finalizados = Requisicao::whereIn('status', ['Compra Finalizada'])->where('user_criacao_id', $user->id)->count();

        return view('dashboard/index', compact('preparar_compra_requisicoes',
        'validacao_requisicoes','autorizacao_requisicoes','user','nr_pedidos',
        'nr_preparos','nr_validacao','nr_autorizacao','nr_aprovados','nr_finalizados'));
    }

    public function perfil(){
        $user = auth()->user();
        return view('dashboard/perfil', compact('user'));
    }

    public function atualizar_foto(Request $request){
        $user = auth()->user();
        if($request->hasFile('imagem') && $request->file('imagem')->isValid()){
            $imagem = $request->imagem;
            $extensao = $imagem->extension();

            $nmImagem = $user->id.".".$extensao;
            $request->imagem->move(public_path('img/users'), $nmImagem);

            $user->imagem = $nmImagem;
            $user->save();

        }
        return redirect()->route('perfil')->with('mensagem', 'Foto Atualizado!');
    }

    public function resetar_foto_perfil(){
        $user = auth()->user();
        $user->imagem = null;
        $user->save();
        return redirect()->route('perfil')->with('mensagem', 'Foto Atualizado!');
    }

    public function perfil_update(Request $request){
        $user = auth()->user();
        $user->nome = $request->nome;
        $user->email = $request->email;
        $user->save();
        return redirect()->route('perfil')->with('mensagem', 'Perfil Atualizado!');
    }

    public function alterar_senha(){
        $user = auth()->user();
        return view('dashboard/alterar_senha', compact('user'));
    }

    public function alterar_senha_update(Request $request){
        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->route('perfil')->with('mensagem', 'Senha Alterada!');
    }
}
