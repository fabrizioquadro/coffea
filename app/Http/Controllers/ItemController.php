<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Grupo;

class ItemController extends Controller
{
    public function index(){
        $itens = Item::all();
        return view('itens/index', compact('itens'));
    }

    public function adicionar(){
        $grupos = Grupo::all()->sortBy('descricao');
        return view('itens/adicionar', compact('grupos'));
    }

    public function insert(Request $request){
        try {
            $dados = $request->except('_token');
            Item::create($dados);
            return redirect()->route('itens')->with('mensagem', 'Item Cadastrado!');
        } catch (\Exception $e) {
            return redirect()->route('itens')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function editar($id){
        $grupos = Grupo::all()->sortBy('descricao');
        $item = Item::where('id', $id)->first();
        return view('itens/editar', compact('grupos','item'));
    }

    public function update(Request $request){
        try {
            $dados = $request->except('_token','item_id');
            Item::where('id', $request->item_id)->update($dados);
            return redirect()->route('itens')->with('mensagem', 'Item Editado!');
        } catch (\Exception $e) {
            return redirect()->route('itens')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function excluir($id){
        $item = Item::where('id', $id)->first();
        return view('itens/excluir', compact('item'));
    }

    public function delete(Request $request){
        try {
            Item::where('id', $request->item_id)->delete();
            return redirect()->route('itens')->with('mensagem', 'Item Excluído!');
        } catch (\Exception $e) {
            return redirect()->route('itens')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function busca_por_grupo(){
        $itens = Item::where('grupo_id', $_GET['grupo_id'])->orderBy('nome')->get();
        $html = "<option value=''>Opções</option>";
        foreach($itens as $item){
            $html .= "<option value='$item->id'>$item->nome</option>";
        }
        $retorno['items'] = $html;
        echo json_encode($retorno);
    }
}
