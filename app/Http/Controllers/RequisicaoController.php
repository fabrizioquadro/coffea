<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;
use App\Models\RequisicaoItem;
use App\Models\RequisicaoAnexo;
use App\Models\Fornecedor;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\User;
use App\Models\Grupo;
use App\Models\Financeiro;
use App\Models\Operacao;
use App\Models\Item;
use App\Models\Qrcode;
use App\Models\Token;
use App\Models\Perfil;
use App\Models\Alerta;
use App\Models\ContaPagamento AS Conta;

class RequisicaoController extends Controller
{
    public function index(){
        $in = ['Pedido','Pedido Cancelado','Compra Aprovada','Compra Finalizada','Compra Cancelada'];
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
        return view('requisicoes/index', compact('requisicoes','user'));
    }

    public function adicionar(){
        $user = auth()->user();
        if($user->perfil->administrador || $user->perfil->criar){
            if($user->perfil->administrador){
                $setores = Setor::where('status','Ativo')->orderBy('nome')->get();
                $unidades = Unidade::where('status','Ativo')->orderBy('nome')->get();
            }
            else{
                $setores = $user->setores;
                $unidades = $user->unidades;
            }
            $fornecedores = Fornecedor::all()->sortBy('nome');
            $users = User::all()->sortBy('nome');
            $grupos = Grupo::all()->sortBy('descricao');

            return view('requisicoes/adicionar', compact('fornecedores',
            'setores','unidades','users','grupos'));
        }
    }

    public function insert(Request $request){
        $user = auth()->user();
        $dados = [
            'fornecedor_id' => $request->fornecedor_id,
            'setor_id' => $request->setor_id,
            'unidade_id' => $request->unidade_id,
            'user_moderador_id' => $request->user_moderador_id,
            'user_liberador_id' => $request->user_liberador_id,
            'user_criacao_id' => $user->id,
            'simples_cotacao' => $request->simples_cotacao == 'Sim' ? true : false,
            'motivo_pedido_compra' => $request->motivo_pedido_compra,
            'justificativa' => $request->justificativa,
            'subtotal_pedido' => valorFormDb($request->subtotal_pedido),
            'frete' => valorFormDb($request->frete),
            'outras_despesas' => valorFormDb($request->outras_despesas),
            'desconto' => valorFormDb($request->desconto),
            'acrescimo' => valorFormDb($request->acrescimo),
            'total_pedido' => valorFormDb($request->total_pedido),
            'qtd_itens_pedido' => $request->qtd_itens_pedido,
            'data_previa_conclusao' => $request->data_previa_conclusao,
            'status' => 'Solicitado',
        ];

        $requisicao = Requisicao::create($dados);

        for($i=1 ; $i<=$request->contador_items ; $i++){
            $var = "item_id_".$i;
            $item_id = $request->$var;
            if($item_id){
                $var = "qtd_pedida_".$i;
                $qtd_pedida = $request->$var;

                $var = "valor_unid_".$i;
                $valor_unid = $request->$var;

                $var = "valor_total_".$i;
                $valor_total = $request->$var;

                $var = "data_previsao_entrega_".$i;
                $data_previsao_entrega = $request->$var;

                $var = "obs_".$i;
                $obs = $request->$var;

                $var = "lancar_patrimonio".$i;
                $lancar_patrimonio = $request->$var;

                $dados = [
                    'requisicao_id' => $requisicao->id,
                    'item_id' => $item_id,
                    'user_criacao_id' => $user->id,
                    'obs' => $obs,
                    'valor_unid' => valorFormDb($valor_unid),
                    'qtd_pedida' => $qtd_pedida,
                    'data_previsao_entrega' => $data_previsao_entrega,
                    'qtd_total' => $qtd_pedida,
                    'valor_total_pedido' => valorFormDb($valor_total),
                    'status' => 'Pedido',
                    'lancar_patrimonio' => $lancar_patrimonio == "Sim" ? true : false,
                ];

                RequisicaoItem::create($dados);
            }
        }

        for($i=1 ; $i<=$request->contador_anexos ; $i++){
            $var = "anexo_fornecedor_".$i;
            $anexo_fornecedor = $request->$var;
            $arq = "anexo_arquivo_".$i;
            if($anexo_fornecedor && $request->hasFile($arq) && $request->file($arq)->isValid()){
                $anexo = $request->$arq;

                $extensao = $anexo->extension();

                $link_anexo = 'Anexo_'.$requisicao->id."_".$anexo_fornecedor."_".$i.".".$extensao;
                $anexo->move(public_path('anexo_requisicoes/'.$requisicao->id), $link_anexo);

                $dados = [
                    'requisicao_id' => $requisicao->id,
                    'fornecedor_id' => $anexo_fornecedor,
                    'user_criacao_id' => $user->id,
                    'link_anexo' => $link_anexo,
                ];

                RequisicaoAnexo::create($dados);
            }
        }

        return redirect()->route('requisicoes')->with('mensagem','Requisição Cadastrada!');
    }

    public function editar($id){
        $user = auth()->user();
        if($user->perfil->administrador || $user->perfil->editar){
            if($user->perfil->administrador){
                $setores = Setor::where('status','Ativo')->orderBy('nome')->get();
                $unidades = Unidade::where('status','Ativo')->orderBy('nome')->get();
            }
            else{
                $setores = $user->setores;
                $unidades = $user->unidades;
            }
            $requisicao = Requisicao::where('id', $id)->first();

            $fornecedores = Fornecedor::all()->sortBy('nome');
            $users = User::all()->sortBy('nome');
            $grupos = Grupo::all()->sortBy('descricao');
            $contas = Conta::where(['unidade_id' => $requisicao->unidade_id, 'cred_deb' => 'D'])->orderBy('descricao')->get();;
            $operacoes = Operacao::where('status','Ativo')->orderBy('descricao')->get();

            $controle = 'editar';

            return view('requisicoes/editar', compact('fornecedores',
            'setores','unidades','users','grupos','requisicao','controle',
            'contas','operacoes'));
        }
        else{
            return redirect()->route('requisicoes')->with('mensagem_erro','Você não possui acesso a esta função!');
        }
    }

    public function delete_item(){
        $item = RequisicaoItem::where('id', $_GET['item_id'])->first();
        $requisicao = Requisicao::where('id', $item->requisicao_id)->first();

        $requisicao->qtd_itens_pedido -= $item->qtd_pedida;
        $requisicao->subtotal_pedido -= $item->valor_total_pedido;
        $requisicao->total_pedido -= $item->valor_total_pedido;

        $requisicao->save();
        $item->delete();

        $ds_historico = "Item ".$item->item->nome." excluído.";
        set_historico($requisicao->id, $ds_historico, $requisicao->status);

        $retorno['item_id'] = $item->id;
        $retorno['controle'] = 'true';
        echo json_encode($retorno);
    }

    public function delete_anexo(){
        $anexo = RequisicaoAnexo::where('id', $_GET['anexo_id'])->first();
        $anexo->delete();

        $requisicao = Requisicao::where('id', $anexo->requisicao_id)->first();
        $ds_historico = "Anexo do fornecedor ".$anexo->fornecedor->nome." excluído";
        set_historico($requisicao->id, $ds_historico, $requisicao->status);

        $retorno['anexo_id'] = $anexo->id;
        $retorno['controle'] = 'true';

        echo json_encode($retorno);
    }

    public function update(Request $request){
        $user = auth()->user();

        $dados = [
            'fornecedor_id' => $request->fornecedor_id,
            'setor_id' => $request->setor_id,
            'unidade_id' => $request->unidade_id,
            'user_moderador_id' => $request->user_moderador_id,
            'user_liberador_id' => $request->user_liberador_id,
            'user_alteracao_id' => $user->id,
            'simples_cotacao' => $request->simples_cotacao == 'Sim' ? true : false,
            'motivo_pedido_compra' => $request->motivo_pedido_compra,
            'justificativa' => $request->justificativa,
            'subtotal_pedido' => valorFormDb($request->subtotal_pedido),
            'frete' => valorFormDb($request->frete),
            'outras_despesas' => valorFormDb($request->outras_despesas),
            'desconto' => valorFormDb($request->desconto),
            'acrescimo' => valorFormDb($request->acrescimo),
            'total_pedido' => valorFormDb($request->total_pedido),
            'qtd_itens_pedido' => $request->qtd_itens_pedido,
            'data_previa_conclusao' => $request->data_previa_conclusao,
            'fornecedor_email' => $request->fornecedor_email,
        ];

        if($request->controle == 'preparar_compra'){
            $dados['status'] = 'Pedido Compra';
            $mensagem_historico = "Pedido repassado para requisição de compra. ";
        }
        else{
            $mensagem_historico = "Requisição Editada. ";
        }

        Requisicao::where('id', $request->requisicao_id)->update($dados);

        $requisicao = Requisicao::where('id', $request->requisicao_id)->first();

        $itens_edit_historico = "";
        foreach($requisicao->itens as $item){
            $var = "item_cad_qtd_pedida_".$item->id;
            $qtd_pedida = $request->$var;

            $var = "item_cad_valor_unid_".$item->id;
            $valor_unid = $request->$var;

            $var = "item_cad_valor_total_".$item->id;
            $valor_total = $request->$var;

            $var = "item_cad_data_previsao_entrega_".$item->id;
            $data_previsao_entrega = $request->$var;

            $var = "item_cad_obs_".$item->id;
            $obs = $request->$var;

            $var = "item_cad_lancar_patrimonio_".$item->id;
            $lancar_patrimonio = $request->$var;

            $dados = [
                'user_alteracao_id' => $user->id,
                'obs' => $obs,
                'valor_unid' => valorFormDb($valor_unid),
                'qtd_pedida' => $qtd_pedida,
                'data_previsao_entrega' => $data_previsao_entrega,
                'qtd_total' => $qtd_pedida,
                'valor_total_pedido' => valorFormDb($valor_total),
                'lancar_patrimonio' => $lancar_patrimonio == "Sim" ? true : false,
            ];

            RequisicaoItem::where('id', $item->id)->update($dados);

            $itens_edit_historico .= ", ".$item->item->nome;
        }

        $itens_historico = "";
        for($i=1 ; $i<=$request->contador_items ; $i++){
            $var = "item_id_".$i;
            $item_id = $request->$var;
            if($item_id){
                $var = "qtd_pedida_".$i;
                $qtd_pedida = $request->$var;

                $var = "valor_unid_".$i;
                $valor_unid = $request->$var;

                $var = "valor_total_".$i;
                $valor_total = $request->$var;

                $var = "data_previsao_entrega_".$i;
                $data_previsao_entrega = $request->$var;

                $var = "obs_".$i;
                $obs = $request->$var;

                $var = "lancar_patrimonio".$i;
                $lancar_patrimonio = $request->$var;

                $dados = [
                    'requisicao_id' => $requisicao->id,
                    'item_id' => $item_id,
                    'user_criacao_id' => $user->id,
                    'obs' => $obs,
                    'valor_unid' => valorFormDb($valor_unid),
                    'qtd_pedida' => $qtd_pedida,
                    'data_previsao_entrega' => $data_previsao_entrega,
                    'qtd_total' => $qtd_pedida,
                    'valor_total_pedido' => valorFormDb($valor_total),
                    'status' => 'Pedido',
                    'lancar_patrimonio' => $lancar_patrimonio == "Sim" ? true : false,
                ];

                RequisicaoItem::create($dados);

                $item = Item::where('id', $item_id)->first();
                $itens_historico .= ", $item->nome";
            }
        }

        $anexos_historico = "";
        for($i=1 ; $i<=$request->contador_anexos ; $i++){
            $var = "anexo_fornecedor_".$i;
            $anexo_fornecedor = $request->$var;
            $arq = "anexo_arquivo_".$i;
            if($anexo_fornecedor && $request->hasFile($arq) && $request->file($arq)->isValid()){
                $anexo = $request->$arq;

                $extensao = $anexo->extension();

                $link_anexo = 'Anexo_'.$requisicao->id."_".$anexo_fornecedor."_".$i.".".$extensao;
                $anexo->move(public_path('anexo_requisicoes/'.$requisicao->id), $link_anexo);

                $dados = [
                    'requisicao_id' => $requisicao->id,
                    'fornecedor_id' => $anexo_fornecedor,
                    'user_criacao_id' => $user->id,
                    'link_anexo' => $link_anexo,
                ];

                $anexo = RequisicaoAnexo::create($dados);

                $anexos_historico .= ", ".$anexo->fornecedor->nome;
            }
        }

        //vamos fazer a parte financeiro
        //vamos verificar se teve alguma edição nos financeiros
        $financeiro_edit_historico = "";
        foreach($requisicao->financeiros as $financeiro){
            $var = "financeiro_cad_operacao_id_".$financeiro->id;
            $financeiro->operacao_id = $request->$var;
            $var = "financeiro_cad_conta_pagamento_id_".$financeiro->id;
            $financeiro->conta_pagamento_id = $request->$var;
            $var = "financeiro_cad_tipo_pagamento_".$financeiro->id;
            $financeiro->tipo_pagamento = $request->$var;
            $var = "financeiro_cad_descricao_".$financeiro->id;
            $financeiro->descricao = $request->$var;
            $var = "financeiro_cad_vencimento_".$financeiro->id;
            $financeiro->vencimento = $request->$var;
            $var = "financeiro_cad_valor_".$financeiro->id;
            $financeiro->valor = valorFormDb($request->$var);
            $var = "financeiro_cad_doc_".$financeiro->id;
            $financeiro->doc = $request->$var;
            $var = "financeiro_cad_obs_".$financeiro->id;
            $financeiro->obs = $request->$var;

            $financeiro->save();
            $financeiro_edit_historico .= ", ".dataDbForm($financeiro->vencimento)." ".valorDbForm($financeiro->valor);
        }

        $financeiro_historico = "";
        for($i=0 ; $i<=$request->contador_financeiro ; $i++){
            $var = "operacao_id_".$i;
            $operacao_id = $request->$var;

            $var = "conta_pagamento_id_".$i;
            $conta_pagamento_id = $request->$var;

            $var = "tipo_pagamento_".$i;
            $tipo_pagamento = $request->$var;

            $var = "descricao_".$i;
            $descricao = $request->$var;

            $var = "vencimento_".$i;
            $vencimento = $request->$var;

            $var = "valor_".$i;
            $valor = $request->$var;

            $var = "doc_".$i;
            $doc = $request->$var;

            $var = "obs_".$i;
            $obs = $request->$var;

            if($operacao_id && $conta_pagamento_id && $tipo_pagamento && $descricao && $valor){
                $dados = [
                    'requisicao_id' => $requisicao->id,
                    'fornecedor_id' => $requisicao->fornecedor_id,
                    'operacao_id' => $operacao_id,
                    'conta_pagamento_id' => $conta_pagamento_id,
                    'user_criacao_id' => $user->id,
                    'cred_deb' => 'Débito',
                    'tipo_pagamento' => $tipo_pagamento,
                    'origem' => 'Compra',
                    'descricao' => $descricao,
                    'vencimento' => $vencimento,
                    'valor' => valorFormDb($valor),
                    'doc' => $doc,
                    'obs' => $obs,
                ];

                $financeiro = Financeiro::create($dados);
                $financeiro_historico .= ", ".dataDbForm($financeiro->vencimento)." ".valorDbForm($financeiro->valor);
            }
        }

        //vamos salvar o historico
        $ds_historico = $mensagem_historico;

        if($itens_edit_historico != ""){
            $itens_edit_historico = substr($itens_edit_historico,2);
            $ds_historico .= "- Itens Editados: ".$itens_edit_historico;
        }

        if($itens_historico != ""){
            $itens_historico = substr($itens_historico,2);
            $ds_historico .= "- Itens Adicionados: ".$itens_historico;
        }

        if($anexos_historico != ""){
            $anexos_historico = substr($anexos_historico,2);
            $ds_historico .= "- Anexos Adicionados: ".$anexos_historico;
        }

        if($financeiro_edit_historico != ""){
            $financeiro_edit_historico = substr($financeiro_edit_historico,2);
            $ds_historico .= "- Financeiros Editados: ".$financeiro_edit_historico;
        }

        if($financeiro_historico != ""){
            $financeiro_historico = substr($financeiro_historico,2);
            $ds_historico .= "- Financeiros Adicionados: ".$financeiro_historico;
        }

        set_historico($requisicao->id, $ds_historico, $requisicao->status);

        if($request->controle_enviar_moderacao == 'sim'){
            return $this->enviar_para_validacao($request);
            die();
        }

        return redirect()->route('requisicoes')->with('mensagem','Requisição Editada!' );
    }

    public function acessar($id){
        $requisicao = Requisicao::where('id', $id)->first();
        $user = auth()->user();

        Alerta::where('user_id', $user->id)
        ->where('origem','requisicao')
        ->where('requisicao_id',$requisicao->id)
        ->update(['visualizacao' => 'Sim']);

        $view_tipo_pagamento = [
            'Pagamento Antecipado' => 'Antecipado',
            'Pagamento Pós Entrega' => 'Avista',
            'Pagamento Data Vencimento' => 'A Prazo',
        ];
        if($user->perfil_administrador || $user->perfil->acompanhar || ($requisicao->status == "Solicitado" && $requisicao->user_moderador_id == $user->id) || ($requisicao->status == "Moderado" && $requisicao->user_liberador_id == $user->id) || ($user->perfil->acompanhar && $requisicao->user_criacao_id == $user->id)){
            return view('requisicoes/acessar', compact('user','requisicao','view_tipo_pagamento'));
        }
        else{
            return redirect()->route('requisicoes')->with('mensagem_erro','Você não possui acesso a esta função!');
        }
    }

    public function moderacao(Request $request){
        $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
        $requisicao->mensagem = $request->mensagem;
        if($request->retornar_para_compra == 'true'){
            $requisicao->status = 'Pedido Compra';
            $requisicao->save();
            return redirect()->route('requisicoes')->with('mensagem','Requisição Retornada para Compra!');
        }
        elseif($request->enviar_para_aprovacao == 'true'){
            $requisicao->status = 'Moderado';
            $requisicao->save();
            return redirect()->route('requisicoes')->with('mensagem','Requisição Enviada para Liberação!');
        }
    }

    public function retornar_para_moderacao(Request $request){
        $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
        $requisicao->mensagem = $request->mensagem;
        $requisicao->status = 'Solicitado';

        $requisicao->save();
        return redirect()->route('requisicoes')->with('mensagem','Requisição Retornada para Moderação!');
    }

    public function liberacao(Request $request){
        $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
        $requisicao->mensagem = $request->mensagem;
        if($request->retornar_para_moderador == 'true'){
            $requisicao->status = 'Retornado ao Moderador';
            $requisicao->save();
            return redirect()->route('requisicoes')->with('mensagem','Requisição Retornada ao Moderador!');
        }
        elseif($request->liberar_requisicao == 'true'){
            $requisicao->status = 'Aprovado';
            $requisicao->save();
            return redirect()->route('requisicoes')->with('mensagem','Requisição Aprovada!');
        }
    }

    public function financeiro_delete(){
        $financeiro = Financeiro::where('id', $_GET['financeiro_id'])->first();
        $requisicao = Requisicao::where('id', $financeiro->requisicao_id)->first();
        $financeiro->delete();
        $retorno['controle'] = 'true';
        $retorno['financeiro_id'] = $financeiro->id;

        $ds_historico = "Excluído o financeiro ".dataDbForm($financeiro->vencimento)." ".valorDbForm($financeiro->valor);
        set_historico($requisicao->id, $ds_historico, $requisicao->status);
        echo json_encode($retorno);
    }

    public function cancelar_requisicao($id){
        $user = auth()->user();
        if($user->perfil->cancelar){
            $requisicao = Requisicao::where('id', $id)->first();
            $requisicao->status = "Pedido Cancelado";
            $requisicao->mensagem = $_GET['mensagem'];
            $requisicao->save();

            $ds_historico = "Pedido Compra Cancelado";
            $ds_historico .= $_GET['mensagem'] ? " - Mensagem:".$_GET['mensagem'] : "";
            set_historico($requisicao->id, $ds_historico, $requisicao->status);

            return redirect()->route('requisicoes')->with('mensagem','Requisição Cancelada');
        }
    }

    public function retornar_para_compra($id){
        $user = auth()->user();
        $requisicao = Requisicao::where('id', $id)->first();
        $requisicao->status = "Retornado para Compra";
        $requisicao->mensagem = $_GET['mensagem'];
        $requisicao->save();

        $ds_historico = "Pedido Retornado para Compra";
        $ds_historico .= $_GET['mensagem'] ? " - Mensagem:".$_GET['mensagem'] : "";
        set_historico($requisicao->id, $ds_historico, $requisicao->status);

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
                'mensagem' => 'Pedido retornado para análise de compra.',
            ];
            cadastra_alerta($dados_alerta);
        }

        return redirect()->route('requisicoes')->with('mensagem','Requisição Retornada para Compra');
    }

    public function retornar_para_validacao($id){
        $user = auth()->user();
        $requisicao = Requisicao::where('id', $id)->first();
        $requisicao->status = "Retornado para Validação";
        $requisicao->mensagem = $_GET['mensagem'];
        $requisicao->save();

        $ds_historico = "Pedido Retornado para Validação";
        $ds_historico .= $_GET['mensagem'] ? " - Mensagem:".$_GET['mensagem'] : "";
        set_historico($requisicao->id, $ds_historico, $requisicao->status);

        $dados_alerta = [
            'user_id' => $requisicao->user_moderador_id,
            'requisicao_id' => $requisicao->id,
            'origem' => 'requisicao',
            'mensagem' => 'Pedido retornado para moderação.',
        ];
        cadastra_alerta($dados_alerta);

        return redirect()->route('requisicoes')->with('mensagem','Requisição Retornada para Validação');
    }

    public function enviar_para_validacao(Request $request){
        try {
            $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
            $requisicao->status = "Em Validação";
            $requisicao->mensagem = $request->mensagem;
            $requisicao->save();

            $ds_historico = "Requisição enviada para validação.";
            set_historico($requisicao->id, $ds_historico, $requisicao->status);

            //vamos enviar mensagem para o moderador desta requisição
            $dados_alerta = [
                'user_id' => $requisicao->user_moderador_id,
                'requisicao_id' => $requisicao->id,
                'origem' => 'requisicao',
                'mensagem' => 'Novo pedido em moderação para análise.',
            ];
            cadastra_alerta($dados_alerta);

            return redirect()->route('requisicoes')->with('mensagem', 'Requisição Enviada para Validação');
        } catch (\Exception $e) {
            return redirect()->route('requisicoes')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function enviar_para_autorizacao(Request $request){
        try {
            $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
            $requisicao->status = "Em Autorização";
            $requisicao->mensagem = $request->mensagem;
            $requisicao->save();

            $ds_historico = "Requisição enviada para autorização.";
            set_historico($requisicao->id, $ds_historico, $requisicao->status);

            $dados_alerta = [
                'user_id' => $requisicao->user_moderador_id,
                'requisicao_id' => $requisicao->id,
                'origem' => 'requisicao',
                'mensagem' => 'Pedido enviado para autorização.',
            ];
            cadastra_alerta($dados_alerta);

            return redirect()->route('requisicoes')->with('mensagem', 'Requisição Enviada para Validação');
        } catch (\Exception $e) {
            return redirect()->route('requisicoes')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function autorizar_compra(Request $request){
        //vamos gerar o token de ativação
        try {
            $user = auth()->user();
            if($user->perfil->administrador || $user->perfil->aprovar){
                $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
                $requisicao->status = "Compra Aprovada";
                $requisicao->mensagem = $request->mensagem;
                $requisicao->save();

                $data_hora = new \DateTime(date('Y-m-d H:i:s'));
                $data_hora->add(new \DateInterval('PT2H'));
                $vencimento = $data_hora->format('Y-m-d H:i:s');

                $verificador = createPassword(8,false,false,true,false);

                $dados = [
                    'requisicao_id' => $requisicao->id,
                    'user_criacao_id' => $user->id,
                    'vencimento' => $vencimento,
                    'verificador' => $verificador,
                ];

                Token::create($dados);

                $link = $requisicao->id.$requisicao->fornecedor->id.date('YmdHis');
                $link_view   = route('acesso_fornecedor', $link);

                $mensagem = "
                <h2>Código de Ativação para Confirmar Pedido</h2>
                <p>
                    Codigo de ativação da compra: $verificador <br>
                    Link para Ativação: $link_view;
                </p>
                ";

                $requisicao->dt_hr_envio_email_fornecedor = date('Y-m-d H:i:s');
                $requisicao->save();

                enviarMail($requisicao->fornecedor_email, 'Código Ativação Pedido', $mensagem);

                $ds_historico = "Requisição Aprovada, enviado token para aprovação fornecedor.";
                set_historico($requisicao->id, $ds_historico, $requisicao->status);

                $dados = [
                    'requisicao_id' => $requisicao->id,
                    'link' => $link,
                    'vencimento' => $requisicao->data_previa_conclusao." 23:59:59",
                ];

                Qrcode::create($dados);

                //vamos enviar para quem criou o pedido e para quem moderou que o pedido foi aprovado
                $dados_alerta = [
                    'user_id' => $requisicao->user_criacao_id,
                    'requisicao_id' => $requisicao->id,
                    'origem' => 'compra',
                    'mensagem' => 'Seu pedido foi aprovado.',
                ];
                cadastra_alerta($dados_alerta);

                $dados_alerta = [
                    'user_id' => $requisicao->user_moderador_id,
                    'requisicao_id' => $requisicao->id,
                    'origem' => 'compra',
                    'mensagem' => 'O pedido a qual você fez a análise de validação foi aprovado.',
                ];
                cadastra_alerta($dados_alerta);

                return redirect()->route('compras')->with('mensagem', 'Compra Aprovada');
            }
        } catch (\Exception $e) {
            return redirect()->route('requisicoes')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function autorizar_compra_old(Request $request){
        //vamos gerar o token de ativação
        try {
            $user = auth()->user();
            if($user->perfil->administrador || $user->perfil->aprovar){
                $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
                $requisicao->status = "Aguardando Token de Aprovação";
                $requisicao->mensagem = $request->mensagem;
                $requisicao->save();

                $data_hora = new \DateTime(date('Y-m-d H:i:s'));
                $data_hora->add(new \DateInterval('PT2H'));
                $vencimento = $data_hora->format('Y-m-d H:i:s');

                $verificador = createPassword(8,false,false,true,false);

                $dados = [
                    'requisicao_id' => $requisicao->id,
                    'user_criacao_id' => $user->id,
                    'vencimento' => $vencimento,
                    'verificador' => $verificador,
                ];

                Token::create($dados);

                $mensagem = "
                <h2>Código de Ativação</h2>
                <p>
                    Codigo de ativação da compra: $verificador
                </p>
                ";

                enviarMail($user->email, 'Código Ativação', $mensagem);

                $ds_historico = "Requisição Aprovada, pedndente token de ativação.";
                set_historico($requisicao->id, $ds_historico, $requisicao->status);

                return redirect()->route('requisicoes.acessar', $requisicao->id)->with('mensagem', 'Código de Ativação enviado para seu email. Validade do código 2 horas.');
            }
        } catch (\Exception $e) {
            return redirect()->route('requisicoes')->with('mensagem_erro', $e->getMessage());
        }

    }

    public function ativar_compra_old(Request $request){
        try {
            $user = auth()->user();
            $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
            $token = Token::where('requisicao_id', $requisicao->id)->first();

            if($request->autorizar_compra == "true"){
                if(strtotime(date('Y-m-d H:i:s')) > strtotime($token->vencimento)){
                    return redirect()->route('requisicoes.acessar', $requisicao->id)->with('mensagem_erro', 'Código expirado!');
                }
                elseif($token->verificador != $request->codigo){
                    return redirect()->route('requisicoes.acessar', $requisicao->id)->with('mensagem_erro', 'Código inválido!');
                }
                else{
                    $requisicao->status = "Compra Aprovada";
                    $requisicao->save();

                    $token->ativacao = date('Y-m-d H:i:s');
                    $token->user_ativacao_id = $user->id;
                    $token->save();

                    $dados = [
                        'requisicao_id' => $requisicao->id,
                        'link' => $requisicao->id.$requisicao->fornecedor->id.date('YmdHis'),
                        'vencimento' => $requisicao->data_previa_conclusao." 23:59:59",
                    ];

                    Qrcode::create($dados);

                    $ds_historico = "Código de ativação confirmado - Compra Aprovada.";
                    set_historico($requisicao->id, $ds_historico, $requisicao->status);

                    return redirect()->route('compras')->with('mensagem', "Compra Aprovada");
                }
            }
            elseif($request->gerar_novo_codigo == 'true'){
                $data_hora = new \DateTime(date('Y-m-d H:i:s'));
                $data_hora->add(new \DateInterval('PT2H'));
                $vencimento = $data_hora->format('Y-m-d H:i:s');

                $verificador = createPassword(8,false,false,true,false);

                $token->vencimento = $vencimento;
                $token->verificador = $verificador;
                $token->save();

                $user = User::where('id', $token->user_criacao_id)->first();

                $mensagem = "
                <h2>Código de Ativação</h2>
                <p>
                    Codigo de ativação da compra: $verificador
                </p>
                ";

                enviarMail($user->email, 'Código Ativação', $mensagem);

                $ds_historico = "Solicitação de novo token de ativação.";
                set_historico($requisicao->id, $ds_historico, $requisicao->status);
                return redirect()->route('requisicoes.acessar', $requisicao->id)->with('mensagem', 'Código de Ativação enviado para seu email. Validade do código 2 horas.');
            }

        } catch (\Exception $e) {
            return redirect()->route('requisicoes')->with('mensagem_erro', $e->getMessage());
        }

    }

}
