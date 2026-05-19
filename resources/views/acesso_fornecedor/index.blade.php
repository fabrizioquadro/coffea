@extends('layout.livre')

@section('conteudo')
<style media="screen">
.borda_de_linha{
    border: 1px solid #dcdcdc;
    border-radius: 10px;
}
label{
    font-size: 10px !important;
}
b{
    font-size: 12px !important;
}
td, th{
    font-size: 10px !important;
}
.card-title{
    font-size: 12px !important;
}
.span_qrCode{
    font-size: 8px !important;
    font-weight: 900 !important;
    width: 50% !important;
}
.span_info{
    font-size: 11px !important;
    font-weight: 900 !important;
    width: 50% !important;
    border: 1px solid #cdcdcd;
    padding: 5px 5px;
    border-radius: 5px;
    background-color: #e5f9f9;
}
.span_aceitar{
    background-color: #41af64;
    color: white;
}
.span_rejeitar{
    background-color: #ff4d49;
    color: white;
}
</style>
@php
$user = auth()->user();
$var = explode(' ', $requisicao->created_at);
$dt_criacao = dataDbForm($var[0])." ".$var[1];

//vamos descobrir quem executou a validação
$dt_validacao = null;
$hist_val = $requisicao->historicos()->where('status','Em Autorização')->orderByDesc('id')->first();
if($hist_val){
    $var = explode(' ', $hist_val->created_at);
    $dt_validacao = dataDbForm($var[0])." ".$var[1];
}

//vamos descobrir quem executou a aprovação
$dt_aprovacao = null;
$hist_aprov = $requisicao->historicos()->where('status','Compra Aprovada')->orderByDesc('id')->first();
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
<div class="card card-border-shadow-secondary mb-4">
    <div class="card-body">
        <div class="row pt-3 pb-3 mb-2">
            <div class="col-md-4 col-sm-12" style="border: 1px solid #cdcdcd; border-radius: 10px; height: 100%" align='center'>
                <img src="/public/img/unidades/{{ $requisicao->unidade->logo }}" class="img-fluid" alt='logo'>
                <h6 class="mt-3">Unidade: <strong>{{ $requisicao->unidade->nome }}</strong></h6>
            </div>
            <div class="col-2 d-none d-md-block" align='center'>
                <img src="{{ $qrcode }}" class="img-fluid"><br>
                <span class="span_qrCode">Escanear o QR Code para acessar a Requisição</span>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="row">
                    @if($user)
                        <div class="col-md-8 col-sm-12">
                            @if(!$requisicao->qrcode()->aceite_fornecedor)
                                <div class="d-flex justify-content-between">
                                    <p class="span_info">
                                        Por favor clique em um dos botões para manifestar sua decisão quanto a este pedido de compra.<br>
                                        <span class='span_aceitar'>Utilizar</span> - para confirmar o aceita da requisição. <br>
                                        <span class="span_rejeitar">Rejeitar</span> - caso não possa atender ao pedido.
                                    </p>
                                    <div align='center'>
                                        <form id='formulario' action="{{ route('manifestacao_fornecedor') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
                                            <input type="hidden" name="validacao" value="{{ $requisicao->qrcode()->link }}">
                                            <input type="hidden" name="acao" id="acao">
                                            <button type="button" id="botao_aceitar" class="mt-3 btn btn-sm btn-primary">Utilizar</button>
                                            <button type="button" id="botao_rejeitar" class="mt-3 btn btn-sm btn-danger">Rejeitar</button>
                                        </form>
                                        <script>
                                        document.getElementById('botao_aceitar').addEventListener('click',()=>{
                                            if(confirm('Tem certeza que deseja aceitar o pedido?')){
                                                document.getElementById('acao').value = "Aceitar";
                                                document.getElementById('formulario').submit();
                                            }
                                        })
                                        document.getElementById('botao_rejeitar').addEventListener('click',()=>{
                                            if(confirm('Tem certeza que deseja rejeitar o pedido?')){
                                                document.getElementById('acao').value = "Rejeitar";
                                                document.getElementById('formulario').submit();
                                            }
                                        })
                                        </script>
                                    </div>
                                </div>
                            @else
                                <p class="text-center">
                                    <span class="mdi mdi-alert-outline"></span><br>
                                    <span><strong> JÁ MANIFESTOU A SUA DECISÃO SOBRE ESTE PEDIDO.</strong></span><br>
                                    Não é possível nova manifestação.
                                </p>
                            @endif
                        </div>
                    @endif
                    <div class="col-md-4 col-sm-12">
                        <h6 class="mt-2">Requisição:</h6>
                        <h1>
                            <strong>{{ $requisicao->id }}</strong><br>
                            <strong style="font-size: 20px">{{ $requisicao->aceito_pelo_fornecedor ? 'Utilizada' : 'Não Utilizada' }}</strong>
                        </h1>
                    </div>
                </div>
            </div>
            {{--
            <div class="col-12 mt-3" align='right'>
                <label for="">Link Verificação Fornecedor:</label><br>
                <a target='_blank' href="{{ $link }}" style="text-decoration: none; color: #696969">{{ $link }}</a>
            </div>
            --}}
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
            <div class="col-md-4 form-group borda_de_linha">
                <label for="user_moderador_id">Solicitante:</label><br>
                <b>{{ $requisicao->criador->nome }}</b><br>
                <b>{{ $dt_criacao }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="user_moderador_id">Validação:</label><br>
                <b>{{ $hist_val ? $hist_val->user->nome : '---' }}</b><br>
                <b>{{ $dt_validacao }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
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
                <b>{{ $requisicao->financeiros ? $requisicao->financeiros->first()->operacao->descricao : '' }}</b>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mt-3 mb-3">
            <h5 class="card-title">Financeiro</h5>
        </div>
        <div id="div_anexos">
            <div class="table-responsive borda_de_linha">
                <table class="table">
                    <thead class="table-light">
                        <th>Tipo</th>
                        <th>Origem</th>
                        <th>Descrição</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Doc</th>
                    </thead>
                    <tbody>
                        @foreach($requisicao->financeiros()->orderBy('vencimento')->get() as $financeiro)
                            <tr>
                                <td>{{ $financeiro->tipo_pagamento }}</td>
                                <td>{{ $financeiro->origem }}</td>
                                <td>{{ $financeiro->descricao }}</td>
                                <td>{{ dataDbForm($financeiro->vencimento) }}</td>
                                <td>R$ {{ valorDbForm($financeiro->valor) }}</td>
                                <td>{{ $financeiro->doc }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
