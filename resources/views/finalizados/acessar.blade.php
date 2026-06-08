@php
$user = auth()->user();
$var = explode(' ', $requisicao->created_at);
$dt_criacao = dataDbForm($var[0])." ".$var[1];

//vamos descobrir quem executou a validação
$dt_validacao = null;
$hist_val = $requisicao->historicos()->where('status','Em Autorização')->orderBy('id')->first();
if($hist_val){
    $var = explode(' ', $hist_val->created_at);
    $dt_validacao = dataDbForm($var[0])." ".$var[1];
}

$dt_compra = null;
$nm_compra = null;
$hist_compra = $requisicao->historicos()->where('status','PEDIDO COMPRA')->orderBy('id')->first();
if($hist_compra){
    $nm_compra = $hist_compra->user->nome;
    $var = explode(' ', $hist_compra->created_at);
    $dt_compra = dataDbForm($var[0])." ".$var[1];
}

//vamos descobrir quem executou a aprovação
$dt_aprovacao = null;
$hist_aprov = $requisicao->historicos()->where('status','Compra Aprovada')->orderBy('id')->first();
if($hist_aprov){
    $var = explode(' ', $hist_aprov->created_at);
    $dt_aprovacao = dataDbForm($var[0])." ".$var[1];
}

$dt_confirmacao = null;
if($requisicao->qrcode()){
    $var = explode(" ", $requisicao->qrcode()->updated_at);
    $dt_confirmacao = dataDbForm($var[0])." ".$var[1];
}


@endphp
@extends('layout.admin')

@section('conteudo')
<style media="screen">
    .borda_de_linha{
        border: 1px solid #dcdcdc;
        border-radius: 10px;
    }
</style>
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Acessar Requisição - Cod: {{ $requisicao->id }}</h4>
        </div>
        <hr>
        @if($mensagem = Session::get('mensagem'))
            <div class="alert alert-success alert-dismissible mt-3 mb-2" role="alert">
                {{ $mensagem }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($mensagem = Session::get('mensagem_erro'))
            <div class="alert alert-danger alert-dismissible mt-3 mb-2" role="alert">
                {{ $mensagem }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <a href="{{ route('compras.imprimir', $requisicao->id) }}" target='_blank' class="btn btn-primary btn-sm">Imprimir Detalhado</a>
        <a href="{{ route('compras.imprimir_simplificado', $requisicao->id) }}" target='_blank' class="btn btn-warning btn-sm">Imprimir Simplificado</a>
        @if($requisicao->sem_validacao)
            <a href="{{ route('requisicoes.editar', [$requisicao->id,'compras']) }}" class="btn btn-warning btn-sm">Editar</a>
        @endif
        @if($requisicao->financeiros()->count() > 0 && $user->perfil->integrar_financeiro)
            <a href="{{ route('compras.integrar', $requisicao->id) }}" class="btn btn-secondary btn-sm">Integrar</a>
        @endif
        <button type="button" id="botao_enviar_email" class="btn btn-sm btn-info">Enviar por Email</button>
        <div class="row align-items-end mt-3 gy-2">
            <div class="col-md-6 form-group">
                <img src="{{ $qrcode }}" height="120px">
            </div>
            <div class="col-md-6 form-group">
                <h5>Requisição: <strong>{{ $requisicao->id }}</strong></h5>
                <label for="">Link Fornecedor:</label>
                <a target='_blank' href="{{ $link }}" style="text-decoration: none; color: #696969">{{ $link }}</a>
            </div>
        </div>
        <div class="row mt-3 gy-2">
            <div class="col-md-8 form-group borda_de_linha">
                <label for="fornecedor_id">Fornecedor:</label><br>
                <b>{{ $requisicao->fornecedor->nome }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="">Status:</label><br>
                <b>{{ $requisicao->status }}</b><br>
            </div>
        </div>
        <div class="row mt-2 gy-2">
            <div class="col-md-6 form-group borda_de_linha">
                <label for="fornecedor_id">Email/Whatsapp Fornecedor:</label><br>
                <b>{{ $requisicao->fornecedor_email." ".$requisicao->fornecedor_whatsapp }}</b>
            </div>
            <div class="col-md-3 form-group borda_de_linha">
                <label for="data_previa_conclusao">Data Prévia Conclusão:</label><br>
                <b>{{ dataDbForm($requisicao->data_previa_conclusao) }}</b>
            </div>
            <div class="col-md-3 form-group borda_de_linha">
                <label for="Utilizada">Utilizada:</label><br>
                <b>{{ $requisicao->aceito_pelo_fornecedor ? 'Sim '.$dt_confirmacao : 'Não' }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2">
            <div class="col-md-3 form-group borda_de_linha">
                <label for="user_moderador_id">Solicitante:</label><br>
                <b>{{ $requisicao->criador->nome }}</b><br>
                <b>{{ $dt_criacao }}</b>
            </div>
            <div class="col-md-3 form-group borda_de_linha">
                <label for="user_moderador_id">Compra:</label><br>
                <b>{{ $nm_compra }}</b><br>
                <b>{{ $dt_compra }}</b>
            </div>
            <div class="col-md-3 form-group borda_de_linha">
                <label for="user_moderador_id">Validação:</label><br>
                <b>{{ $hist_val ? $hist_val->user->nome : '---' }}</b><br>
                <b>{{ $dt_validacao }}</b>
            </div>
            <div class="col-md-3 form-group borda_de_linha">
                <label for="user_liberador_id">Aprovação:</label><br>
                <b>{{ $hist_aprov ? $hist_aprov->user->nome : '---' }}</b><br>
                <b>{{ $dt_aprovacao }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2 borda_de_linha">
            <div class="col-md-4 form-group borda_de_linha">
                <label for="setor_id">Setor:</label><br>
                <b>{{ $requisicao->setor->nome }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="unidade_id">Unidade:</label><br>
                <b>{{ $requisicao->unidade->nome }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="unidade_id">Operação:</label><br>
                <b>{{ $requisicao->financeiros->first() ? $requisicao->financeiros->first()->operacao->descricao : '' }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2 borda_de_linha">
            <div class="col-md-12 form-group">
                <label for="motivo_pedido_compra">Item/Motivo:</label><br>
                <b>{{ $requisicao->motivo_pedido_compra }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2 borda_de_linha">
            <div class="col-md-12 form-group">
                <label for="justificativa">Justificativa:</label><br>
                <b>{{ $requisicao->justificativa }}</b>
            </div>
        </div>
        @if($requisicao->documentos)
        <div class="row mt-2 gy-2 borda_de_linha">
            <div class="col-md-12 form-group">
                <label for="documentos">Documentos:</label><br>
                <b>{!! nl2br(e($requisicao->documentos)) !!}</b>
            </div>
        </div>
        @endif
        <div class="row mt-2 gy-2 align-items-end">
            <div class="col-md-6 form-group borda_de_linha">
                <label for="qtd_itens_pedido">Total Quantidade:</label><br>
                <b>{{ $requisicao->qtd_itens_pedido }}</b>
            </div>
            <div class="col-md-6 form-group borda_de_linha">
                <label for="subtotal_pedido">Subtotal Pedido:</label><br>
                <b>R$ {{ valorDbForm($requisicao->subtotal_pedido) }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="frete">Frete:</label><br>
                <b>R$ {{ valorDbForm($requisicao->frete) }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="outras_despesas">Outras Despesas:</label><br>
                <b>R$ {{ valorDbForm($requisicao->outras_despesas) }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="desconto">Desconto:</label><br>
                <b>R$ {{ valorDbForm($requisicao->desconto) }}</b>
            </div>
            <div class="col-md-6 form-group borda_de_linha">
                <label for="acrescimo">Acrescimo:</label><br>
                <b>R$ {{ valorDbForm($requisicao->acrescimo) }}</b>
            </div>
            <div class="col-md-6 form-group borda_de_linha">
                <label for="total_pedido">Total Pedido:</label><br>
                <b>R$ {{ valorDbForm($requisicao->total_pedido) }}</b>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-3 mb-3">
            <h5 class="card-title">Itens</h5>
        </div>
        <div class="table-responsive borda_de_linha">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th>Unidade</th>
                        <th>Qtd</th>
                        <th>Unitário</th>
                        <th>Total</th>
                        <th>Entrega</th>
                        <th>Obs</th>
                        <th>Patrimonio</th>
                    </tr>
                </thead>
                <tbody id="tabela_items">
                    @foreach($requisicao->itens as $item)
                        <tr id="linha_item_cadastrada_{{ $item->id }}">
                            <td>{{ $item->item->nome }}</td>
                            <td>{{ $item->ds_unidade }}</td>
                            <td>{{ $item->qtd_pedida }}</td>
                            <td>R${{ valorDbForm($item->valor_unid) }}</td>
                            <td>R${{ valorDbForm($item->valor_total_pedido) }}</td>
                            <td>{{ dataDbForm($item->data_previsao_entrega) }}</td>
                            <td>{{ $item->obs }}</td>
                            <td>{{ $item->lancar_patrimonio ? 'Sim' : 'Não' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <div class="d-flex justify-content-between mt-3 mb-3">
            <h5 class="card-title">Anexos</h5>
        </div>
        <div id="div_anexos">
            @if($requisicao->anexos()->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <th>Fornecedor</th>
                            <th>Arquivo</th>
                        </thead>
                        <tbody>
                            @foreach($requisicao->anexos as $anexo)
                                <tr id='linha_anexo_cadastrado_{{ $anexo->id }}'>
                                    <td>{{ $anexo->fornecedor->nome }}</td>
                                    <td>
                                        <a title="Abrir" target='_blank' href="/public/anexo_requisicoes/{{ $anexo->requisicao_id."/".$anexo->link_anexo }}" class="btn rounded-pill btn-icon btn-outline-primary waves-effect">
                                            <span class="tf-icons mdi mdi-folder-outline"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <hr>
        <div class="d-flex justify-content-between mt-3 mb-3">
            <h5 class="card-title">Financeiro</h5>
        </div>
        <div id="div_anexos">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <th>Id</th>
                        <th>Operação</th>
                        <th>Conta Pagamento</th>
                        <th>Cred/Deb</th>
                        <th>Tipo</th>
                        <th>Origem</th>
                        <th>Descrição</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Doc</th>
                        <th>Obs</th>
                    </thead>
                    <tbody>
                        @foreach($requisicao->financeiros()->orderBy('vencimento')->get() as $financeiro)
                            <tr>
                                <td>{{ $financeiro->id }}</td>
                                <td>{{ $financeiro->operacao->descricao }}</td>
                                <td>{{ $financeiro->conta_pagamento->descricao }}</td>
                                <td>{{ $financeiro->cred_deb }}</td>
                                <td>{{ $view_tipo_pagamento[$financeiro->tipo_pagamento] }}</td>
                                <td>{{ $financeiro->origem }}</td>
                                <td>{{ $financeiro->descricao }}</td>
                                <td>{{ dataDbForm($financeiro->vencimento) }}</td>
                                <td>R$ {{ valorDbForm($financeiro->valor) }}</td>
                                <td>{{ $financeiro->doc }}</td>
                                <td>{{ $financeiro->obs }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Histórico</h4>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body pt-4 pb-1">
                        <ul class="timeline card-timeline mb-0">
                            @foreach($requisicao->historicos()->orderByDesc('created_at')->get() as $historico)
                                @php
                                $var = explode(' ', $historico->created_at);
                                $dt_historico = dataDbForm($var[0])." ".$var[1];
                                @endphp
                                <li class="timeline-item timeline-item-transparent">
                                    <span class="timeline-point timeline-point-success"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header mb-1">
                                            <h6 class="mb-2">{{ $historico->user->nome." - Status:".$historico->status }}</h6>
                                            <small class="text-muted">{{ $dt_historico }}</small>
                                        </div>
                                        <p class="mb-2">{{ $historico->ds_historico }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
document.getElementById('botao_enviar_email').addEventListener('click', ()=>{
    email = prompt('Informe o email');
    $.getJSON(
        '{{route("compras.enviar_requisicao_email")}}',
        {
            requisicao_id : {{ $requisicao->id }},
            email : email
        },
        function(json){
            alert('Foi enviado para a fila de envio de emails a solicitação.');
        }
    );
})
</script>

@endsection
