<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;
use App\Models\RequisicaoItem;
use App\Models\RequisicaoAnexoGeral;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\Grupo;
use App\Models\Item;
use App\Models\Fornecedor;
use App\Models\User;
use App\Models\Operacao;
use App\Models\Perfil;
use App\Models\Alerta;
use App\Models\ContaPagamento AS Conta;

class PedidoController extends Controller
{
    public function index(){
        $user = auth()->user();

        if($user->perfil->administrador){
            $requisicoes = Requisicao::whereIn('status', ['Pedido'])->get();
        }
        elseif($user->perfil->preparar_compra){
            $in = array();
            foreach($user->unidades as $unidade){
                $in[] = $unidade->id;
            }
            $requisicoes = Requisicao::where('status', 'Pedido')
            ->whereIn('unidade_id', $in)
            ->get();
        }
        else{
            $requisicoes = Requisicao::whereIn('status', ['Pedido'])
            ->where('user_criacao_id', $user->id)
            ->get();
        }

        $controle = 'index';
        return view('pedidos/index', compact('requisicoes','user','controle'));
    }

    public function cancelados(){
        $user = auth()->user();

        $requisicoes = Requisicao::whereIn('status', ['Pedido Cancelado','Compra Cancelada'])
        ->get();

        $controle = 'cancelados';

        return view('pedidos/index', compact('requisicoes','user','controle'));
    }

    public function adicionar(){
        $user = auth()->user();
        if($user->perfil->administrador || $user->perfil->criar || $user->perfil->somente_solicitar_pedido){
            if($user->perfil->administrador){
                $setores = Setor::where('status','Ativo')->orderBy('nome')->get();
                $unidades = Unidade::where('status','Ativo')->orderBy('nome')->get();
            }
            else{
                $setores = $user->setores;
                $unidades = $user->unidades;
            }
            $grupos = Grupo::all()->sortBy('descricao');

            return view('pedidos/adicionar', compact('setores','unidades',
            'grupos'));
        }
    }

    public function itens_insert(){
        $dados = [
            'grupo_id' => $_GET['grupo_id'],
            'nome' => $_GET['nm_item'],
        ];
        $item_set = Item::create($dados);

        $itens = Item::where('grupo_id', $_GET['grupo_id'])->orderBy('nome')->get();
        $html = "<option value=''>Opções</option>";
        foreach($itens as $item){
            $selected = "";
            if($item->id == $item_set->id){
                $selected = "selected";
            }
            $html .= "<option value='$item->id' $selected>$item->nome</option>";
        }


        $retorno['html'] = $html;
        echo json_encode($retorno);
    }

    public function grupos_insert(){
        $dados = [
            'descricao' => $_GET['nm_grupo'],
        ];
        $grupo_set = Grupo::create($dados);

        $grupos = Grupo::all()->sortBy('descricao');
        $html = "<option value=''>Opções</option>";
        foreach($grupos as $grupo){
            $selected = "";
            if($grupo->id == $grupo_set->id){
                $selected = "selected";
            }
            $html .= "<option value='$grupo->id' $selected>$grupo->descricao</option>";
        }


        $retorno['html'] = $html;
        echo json_encode($retorno);
    }

    public function insert(Request $request){
        $user = auth()->user();
        try {
            $dados = [
                'setor_id' => $request->setor_id,
                'unidade_id' => $request->unidade_id,
                'user_criacao_id' => $user->id,
                'simples_cotacao' => $request->simples_cotacao == 'Sim' ? true : false,
                'motivo_pedido_compra' => $request->motivo_pedido_compra,
                'justificativa' => $request->justificativa,
                'qtd_itens_pedido' => $request->qtd_itens_pedido ? $request->qtd_itens_pedido : '0',
                'status' => 'Pedido',
            ];

            $requisicao = Requisicao::create($dados);

            $itens_historico = "";
            for($i=1 ; $i<=$request->contador_items ; $i++){
                $var = "item_id_".$i;
                $item_id = $request->$var;
                if($item_id){
                    $var = "qtd_pedida_".$i;
                    $qtd_pedida = $request->$var;

                    $var = "obs_".$i;
                    $obs = $request->$var;

                    $var = "ds_unidade_".$i;
                    $ds_unidade = $request->$var;

                    $var = "lancar_patrimonio".$i;
                    $lancar_patrimonio = $request->$var;

                    $dados = [
                        'requisicao_id' => $requisicao->id,
                        'item_id' => $item_id,
                        'user_criacao_id' => $user->id,
                        'obs' => $obs,
                        'ds_unidade' => $ds_unidade,
                        'qtd_pedida' => $qtd_pedida,
                        'qtd_total' => $qtd_pedida,
                        'status' => 'Pedido',
                        'lancar_patrimonio' => $lancar_patrimonio == "Sim" ? true : false,
                    ];

                    RequisicaoItem::create($dados);

                    $item = Item::where('id', $item_id)->first();
                    $itens_historico .= ", $item->nome";
                }
            }
            $itens_historico = substr($itens_historico, 2);
            $ds_historico = "Pedido Aberto com os itens: ".$itens_historico;
            set_historico($requisicao->id, $ds_historico, $requisicao->status);

            if($request->has('contador_anexos_gerais')){
                for($i=1 ; $i<=$request->contador_anexos_gerais ; $i++){
                    $arq = "anexo_geral_arquivo_".$i;
                    if($request->hasFile($arq) && $request->file($arq)->isValid()){
                        $anexo = $request->$arq;
                        $extensao = $anexo->extension();
                        $link_anexo = 'Anexo_Geral_'.$requisicao->id."_".$i."_".time().".".$extensao;
                        $anexo->move(public_path('anexo_requisicoes/'.$requisicao->id), $link_anexo);

                        $dados = [
                            'requisicao_id' => $requisicao->id,
                            'user_criacao_id' => $user->id,
                            'link_anexo' => $link_anexo,
                        ];

                        RequisicaoAnexoGeral::create($dados);
                    }
                }
            }

            //vamos setar para os que fazem p preparo do pedido que tem pedido novo
            $perfis = Perfil::where('preparar_compra', true)->get();
            $in = array();
            foreach($perfis as $perfil){
                $in[] = $perfil->id;
            }
            $users = User::whereIn('perfil_id', $in)->get();
            foreach($users as $user){
                $dados_alerta = [
                    'user_id' => $user->id,
                    'requisicao_id' => $requisicao->id,
                    'origem' => 'pedidos',
                    'mensagem' => 'Novo pedido cadastrado no sistema.',
                ];
                cadastra_alerta($dados_alerta);
            }

            $user = auth()->user();

            if($user->perfil->somente_solicitar_pedido){
                return redirect()->route('pedidos.adicionar')->with('mensagem', "Pedido Cadastrado!");
            }
            else{
                return redirect(session('url_retorno', route('pedidos')))->with('mensagem', "Pedido Cadastrado!");
            }
        } catch (\Exception $e) {
            return redirect(session('url_retorno', route('pedidos')))->with('mensagem_erro', $e->getMessage());
        }
    }

    public function acessar($id){
        $user = auth()->user();
        $requisicao = Requisicao::where('id', $id)->first();
        Alerta::where('user_id', $user->id)
        ->where('origem','pedidos')
        ->where('requisicao_id',$requisicao->id)
        ->update(['visualizacao' => 'Sim']);

        return view('pedidos/acessar', compact('requisicao','user'));
    }

    public function preparar_compra(Request $request){
        $user = auth()->user();
        if($user->perfil->administrador || $user->perfil->preparar_compra){
            $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
            if($request->preparar_compra == 'true'){
                if($user->perfil->administrador){
                    $setores = Setor::where('status','Ativo')->orderBy('nome')->get();
                    $unidades = Unidade::where('status','Ativo')->orderBy('nome')->get();
                }
                else{
                    $setores = $user->setores;
                    $unidades = $user->unidades;
                }

                if($requisicao->unidade->restrita == "Não"){
                    $fornecedores = Fornecedor::whereNull('unidade_id')->orderBy('nome')->get();
                    $operacoes = Operacao::where('status','Ativo')->whereNull('unidade_id')->orderBy('descricao')->get();
                }
                elseif($requisicao->unidade->restrita == "Sim"){
                    $fornecedores = Fornecedor::where('unidade_id',$requisicao->unidade_id)->orderBy('nome')->get();
                    $operacoes = Operacao::where('status','Ativo')->where('unidade_id', $requisicao->unidade_id)->orderBy('descricao')->get();
                }

                $users = User::all()->sortBy('nome');
                $grupos = Grupo::all()->sortBy('descricao');
                $controle = 'preparar_compra';

                $contas = Conta::where('unidade_id', $requisicao->unidade_id)->whereIn('cred_deb', ['D', 'C'])->orderBy('descricao')->get();
                //$operacoes = Operacao::where('status','Ativo')->orderBy('descricao')->get();
                $retorno = null;
                return view('requisicoes/editar', compact('requisicao','setores','unidades',
                'fornecedores','users','grupos','controle','contas','operacoes','retorno'));
            }
            elseif($request->cancelar_compra == 'true'){
                $requisicao->status = 'Pedido Cancelado';
                $requisicao->save();

                $ds_historico = "Pedido Cancelado.";
                set_historico($requisicao->id, $ds_historico, $requisicao->status);
                return redirect(session('url_retorno', route('pedidos')))->with('mensagem','Pedido Cancelado');
            }
        }
    }

    public function editar($id){
        $requisicao = Requisicao::where('id', $id)->first();
        $user = auth()->user();
        if($user->perfil->administrador){
            $setores = Setor::where('status','Ativo')->orderBy('nome')->get();
            $unidades = Unidade::where('status','Ativo')->orderBy('nome')->get();
        }
        else{
            $setores = $user->setores;
            $unidades = $user->unidades;
        }
        $grupos = Grupo::all()->sortBy('descricao');

        return view('pedidos/editar', compact('requisicao','setores','unidades','grupos'));
    }

    public function update(Request $request){
        $user = auth()->user();
        try {
            $dados = [
                'setor_id' => $request->setor_id,
                'unidade_id' => $request->unidade_id,
                'user_alteracao_id' => $user->id,
                'simples_cotacao' => $request->simples_cotacao == 'Sim' ? true : false,
                'motivo_pedido_compra' => $request->motivo_pedido_compra,
                'justificativa' => $request->justificativa,
                'qtd_itens_pedido' => $request->qtd_itens_pedido,
            ];

            Requisicao::where('id', $request->requisicao_id)->update($dados);
            $requisicao = Requisicao::where('id', $request->requisicao_id)->first();

            $itens_historico = "";
            for($i=1 ; $i<=$request->contador_items ; $i++){
                $var = "item_id_".$i;
                $item_id = $request->$var;
                if($item_id){
                    $var = "qtd_pedida_".$i;
                    $qtd_pedida = $request->$var;

                    $var = "obs_".$i;
                    $obs = $request->$var;

                    $var = "ds_unidade_".$i;
                    $ds_unidade = $request->$var;

                    $var = "lancar_patrimonio".$i;
                    $lancar_patrimonio = $request->$var;

                    $dados = [
                        'requisicao_id' => $requisicao->id,
                        'item_id' => $item_id,
                        'user_criacao_id' => $user->id,
                        'obs' => $obs,
                        'ds_unidade' => $ds_unidade,
                        'qtd_pedida' => $qtd_pedida,
                        'qtd_total' => $qtd_pedida,
                        'status' => 'Pedido',
                        'lancar_patrimonio' => $lancar_patrimonio == "Sim" ? true : false,
                    ];

                    RequisicaoItem::create($dados);

                    $item = Item::where('id', $item_id)->first();
                    $itens_historico .= ", $item->nome";
                }
            }

            if($itens_historico != ""){
                $itens_historico = substr($itens_historico, 2);
                $ds_historico = "Pedido Editado, Itens adicionados: ".$itens_historico;
            }
            else{
                $ds_historico = "Pedido Editado.";
            }

            if($request->has('contador_anexos_gerais')){
                for($i=1 ; $i<=$request->contador_anexos_gerais ; $i++){
                    $arq = "anexo_geral_arquivo_".$i;
                    if($request->hasFile($arq) && $request->file($arq)->isValid()){
                        $anexo = $request->$arq;
                        $extensao = $anexo->extension();
                        $link_anexo = 'Anexo_Geral_'.$requisicao->id."_".$i."_".time().".".$extensao;
                        $anexo->move(public_path('anexo_requisicoes/'.$requisicao->id), $link_anexo);

                        $dados = [
                            'requisicao_id' => $requisicao->id,
                            'user_criacao_id' => $user->id,
                            'link_anexo' => $link_anexo,
                        ];

                        RequisicaoAnexoGeral::create($dados);
                    }
                }
            }

            set_historico($requisicao->id, $ds_historico, $requisicao->status);
            return redirect(session('url_retorno', route('pedidos')))->with('mensagem', "Pedido Editado!");
        } catch (\Exception $e) {
            return redirect(session('url_retorno', route('pedidos')))->with('mensagem_erro', $e->getMessage());
        }
    }

    public function verifica_motivo_compra(){
        $requisicao = Requisicao::where('motivo_pedido_compra', $_GET['motivo'])->first();
        $controle = 'false';
        if($requisicao){
            $controle = 'true';
        }
        $retorno['controle'] = $controle;
        echo json_encode($retorno);
    }

    public function delete_anexo_geral(){
        $anexo = RequisicaoAnexoGeral::where('id', $_GET['anexo_id'])->first();
        if($anexo){
            $anexo_id = $anexo->id;
            try{
                unlink(public_path('anexo_requisicoes/'.$anexo->requisicao_id.'/'.$anexo->link_anexo));
            } catch (\Exception $e) {}
            
            $anexo->delete();
            $retorno['controle'] = 'true';
            $retorno['anexo_id'] = $anexo_id;
            echo json_encode($retorno);
        }
    }

    public function listagem(){
        $user = auth()->user();
        $array_requisicoes = array();
        if($user->perfil->administrador || $user->perfil->preparar_compra){
            $requisicoes = Requisicao::whereIn('status', ['Pedido'])->get();
        }
        else{
            $requisicoes = Requisicao::whereIn('status', ['Pedido'])
            ->where('user_criacao_id', $user->id)
            ->get();
        }

        foreach($requisicoes as $requisicao){
            $array_requisicoes[] = $requisicao;
        }

        $requisicoes = Requisicao::whereIn('status', ['Pedido Cancelado','Compra Cancelada'])
        ->get();

        foreach($requisicoes as $requisicao){
            $array_requisicoes[] = $requisicao;
        }

        $in = ['Compra Aprovada','Compra Finalizada'];
        $user = auth()->user();
        if($user->perfil->administrador){
            $requisicoes = Requisicao::whereNotIn('status',$in)->get();
        }
        elseif($user->perfil->preparar_compra){
            $requisicoes = Requisicao::whereIn('status',['Pedido Compra','Retornado para Compra'])->get();
        }
        elseif($user->perfil->moderar){
            $requisicoes = Requisicao::whereIn('status',['Em Validação','Retornado para Validação'])
            ->where('user_moderador_id', $user->id)
            ->get();
        }
        elseif($user->perfil->aprovar){
            $requisicoes = Requisicao::whereIn('status',['Em Autorização','Aguardando Token de Aprovação'])
            ->where('user_liberador_id', $user->id)
            ->get();
        }

        foreach($requisicoes as $requisicao){
            $array_requisicoes[] = $requisicao;
        }

        if($user->perfil->administrador || $user->perfil->confirmar_recebimento){
            $requisicoes = Requisicao::whereIn('status', ['Compra Aprovada'])->get();
        }
        elseif($user->perfil->moderar){
            $requisicoes = Requisicao::whereIn('status', ['Compra Aprovada'])
            ->where('user_moderador_id', $user->id)
            ->get();
        }

        foreach($requisicoes as $requisicao){
            $array_requisicoes[] = $requisicao;
        }

        if($user->perfil->administrador || $user->perfil->confirmar_recebimento){
            $requisicoes = Requisicao::whereIn('status', ['Compra Finalizada'])->get();
        }
        elseif($user->perfil->moderar){
            $requisicoes = Requisicao::whereIn('status', ['Compra Finalizada'])
            ->where('user_moderador_id', $user->id)
            ->get();
        }

        foreach($requisicoes as $requisicao){
            $array_requisicoes[] = $requisicao;
        }

        return view('pedidos/listagem', compact('array_requisicoes','user'));
    }

}
