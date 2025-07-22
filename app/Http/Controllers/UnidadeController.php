<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidade;

class UnidadeController extends Controller
{
    public function index(){
        $unidades = Unidade::all();
        return view('unidades/index', compact('unidades'));
    }

    public function adicionar(){
        return view('unidades/adicionar');
    }

    public function insert(Request $request){
        try {
            $dados = $request->except('_token','logo');
            $unidade = Unidade::create($dados);

            if($request->hasFile('logo') && $request->file('logo')->isValid()){
                $imagem = $request->logo;
                $extensao = $imagem->extension();

                $nm_imagem = $unidade->id.".".$extensao;
                $request->logo->move(public_path('img/unidades'), $nm_imagem);

                $unidade->logo = $nm_imagem;
                $unidade->save();
            }

            return redirect()->route('unidades')->with('mensagem', 'Unidade Cadastrada!');
        } catch (\Exception $e) {
            return redirect()->route('unidades')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function editar($id){
        $unidade = Unidade::where('id', $id)->first();
        return view('unidades/editar', compact('unidade'));
    }

    public function update(Request $request){
        try {
            $dados = $request->except('_token','unidade_id');
            Unidade::where('id', $request->unidade_id)->update($dados);
            $unidade = Unidade::where('id', $request->unidade_id)->first();

            if($request->hasFile('logo') && $request->file('logo')->isValid()){
                $imagem = $request->logo;
                $extensao = $imagem->extension();

                $nm_imagem = $unidade->id.".".$extensao;
                $request->logo->move(public_path('img/unidades'), $nm_imagem);

                $unidade->logo = $nm_imagem;
                $unidade->save();
            }

            return redirect()->route('unidades')->with('mensagem', 'Unidade Editado!');
        } catch (\Exception $e) {
            return redirect()->route('unidades')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function excluir($id){
        $unidade = Unidade::where('id', $id)->first();
        return view('unidades/excluir', compact('unidade'));
    }

    public function delete(Request $request){
        try {
            Unidade::where('id', $request->unidade_id)->delete();
            return redirect()->route('unidades')->with('mensagem', 'Unidade Excluída!');
        } catch (\Exception $e) {
            return redirect()->route('unidades')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function visualizar($id){
        $unidade = Unidade::where('id', $id)->first();
        return view('unidades/visualizar', compact('unidade'));
    }

    public function testar_token($id){
        $unidade = Unidade::where('id', $id)->first();
        $sisagil = new ApiSisAgilController($unidade->token_sisagil);
        $sisagil->testa_token_api();
    }
}
