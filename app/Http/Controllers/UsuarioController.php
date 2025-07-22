<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Perfil;
use App\Models\Unidade;
use App\Models\Setor;

class UsuarioController extends Controller
{
    public function index(){
        $users = User::all();
        return view('usuarios/index', compact('users'));
    }

    public function adicionar(){
        $perfis = Perfil::where('status','Ativo')->orderBy('descricao')->get();
        $unidades = Unidade::where('status', 'Ativo')->orderBy('nome')->get();
        $setores = Setor::where('status', 'Ativo')->orderBy('nome')->get();
        return view('usuarios/adicionar', compact('perfis','unidades','setores'));
    }

    public function insert(Request $request){
        try {
            $dados = $request->except('_token','password','imagem','unidades','setores');
            $dados['password'] = bcrypt($request->password);
            $user = User::create($dados);
            $user->unidades()->sync($request->unidades);
            $user->setores()->sync($request->setores);

            if($request->hasFile('imagem') && $request->file('imagem')->isValid()){
                $imagem = $request->imagem;
                $extensao = $imagem->extension();

                $nmImagem = $user->id.".".$extensao;
                $request->imagem->move(public_path('img/users'), $nmImagem);

                $user->imagem = $nmImagem;
                $user->save();
            }

            return redirect()->route('usuarios')->with('mensagem', 'Usuário Salvo!');
        }catch(\Exception $e){
            return redirect()->route('usuarios')->with('mensagem_erro',$e->getMessage());
        }
    }

    public function editar($id){
        $usuario = User::where('id', $id)->first();
        $perfis = Perfil::where('status','Ativo')->orderBy('descricao')->get();
        $unidades = Unidade::where('status', 'Ativo')->orderBy('nome')->get();
        $setores = Setor::where('status', 'Ativo')->orderBy('nome')->get();

        return view('usuarios/editar', compact('usuario','perfis','unidades','setores'));
    }

    public function update(Request $request){
        try{
            $dados = $request->except('_token','imagem','user_id','unidades','setores');
            User::where('id', $request->user_id)->update($dados);
            $user = User::where('id', $request->user_id)->first();
            $user->unidades()->sync($request->unidades);
            $user->setores()->sync($request->setores);

            if($request->hasFile('imagem') && $request->file('imagem')->isValid()){
                $imagem = $request->imagem;
                $extensao = $imagem->extension();

                $nmImagem = $user->id.".".$extensao;
                $request->imagem->move(public_path('img/users'), $nmImagem);

                $user->imagem = $nmImagem;
                $user->save();
            }

            return redirect()->route('usuarios')->with('mensagem', 'Usuário Salvo!');

        }catch(\Exception $e){
            return redirect()->route('usuarios')->with('mensagem_erro',$e->getMessage());
        }
    }

    public function excluir($id){
        $usuario = User::where('id', $id)->first();

        return view('usuarios/excluir', compact('usuario'));
    }

    public function delete(Request $request){
        try {
            $user = User::where('id', $request->user_id)->first();
            $user->unidades()->sync([]);
            $user->setores()->sync([]);
            $user->delete();
            return redirect()->route('usuarios')->with('mensagem','Usuário Excluído!');
        }catch(\Exception $e){
            return redirect()->route('usuarios')->with('mensagem_erro',$e->getMessage());
        }
    }

    public function alterar_senha($id){
        $usuario = User::where('id', $id)->first();

        return view('usuarios/alterar_senha', compact('usuario'));
    }

    public function alterar_senha_update(Request $request){
        try{
            $user = User::where('id', $request->user_id)->first();
            $user->password = bcrypt($request->nova_senha);
            $user->save();

            return redirect()->route('usuarios')->with('mensagem','Senha Alterada!');
        }catch(\Exception $e){
            return redirect()->route('usuarios')->with('mensagem_erro',$e->getMessage());
        }
    }
}
