<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setor;

class SetorController extends Controller
{
    public function index(){
        $setores = Setor::all();
        return view('setores/index', compact('setores'));
    }

    public function adicionar(){
        return view('setores/adicionar');
    }

    public function insert(Request $request){
        try {
            $dados = $request->except('_token');
            Setor::create($dados);
            return redirect()->route('setores')->with('mensagem', 'Setor Cadastrado!');
        } catch (\Exception $e) {
            return redirect()->route('setores')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function editar($id){
        $setor = Setor::where('id', $id)->first();
        return view('setores/editar', compact('setor'));
    }

    public function update(Request $request){
        try {
            $dados = $request->except('_token','setor_id');
            Setor::where('id', $request->setor_id)->update($dados);
            return redirect()->route('setores')->with('mensagem', 'Setor Editado!');
        } catch (\Exception $e) {
            return redirect()->route('setores')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function excluir($id){
        $setor = Setor::where('id', $id)->first();
        return view('setores/excluir', compact('setor'));
    }

    public function delete(Request $request){
        try {
            Setor::where('id', $request->setor_id)->delete();
            return redirect()->route('setores')->with('mensagem', 'Setor Excluído!');
        } catch (\Exception $e) {
            return redirect()->route('setores')->with('mensagem_erro', $e->getMessage());
        }
    }
}
