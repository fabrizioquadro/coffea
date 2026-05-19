@extends('layout.imprimir')

@section('conteudo')
<style media="print">
    *{
        padding: 2px !important;
        margin: 0px !important;
    }
    .borda_de_linha{
        border: 1px solid #dcdcdc;
        border-radius: 5px;
    }
    label{
        font-size: 8px !important;
    }
    b{
        font-size: 8px !important;
    }
    td, th{
        font-size: 8px !important;
    }
    .card-title{
        font-size: 10px !important;
        font-weight: 900;
        color: black;
    }
    .span_qrCode{
        font-size: 8px !important;
        font-weight: 900 !important;
        width: 50% !important;
    }
</style>
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


$portador = $requisicao->portador ? $requisicao->portador : '____________________________________';
@endphp
<div class="row pt-3 pb-3 mb-2">
    <div class="col-4" style="border: 1px solid #cdcdcd; border-radius: 10px; height: 100%" align='center'>
        <img src="/public/img/unidades/{{ $requisicao->unidade->logo }}" class="img-fluid" alt='logo'>
        <h6 class="mt-3">Unidade: <strong>{{ $requisicao->unidade->nome }}</strong></h6>
    </div>
    <div class="col-4" align='center'>
        <img src="{{ $qrcode }}" height="80px"><br>
        <span class="span_qrCode">Escanear o QR Code para Acessar a Requisição</span>
    </div>
    <div class="col-4">
        <h6>Requisição:</h6>
        <h1><strong>{{ $requisicao->id }}</strong></h1>
    </div>
</div>
<p style="font-size: 10px">
    Queiram fornecer Sr.(a) {{ $portador }} as seguintes mercadorias descriminadas.
</p>
<div class="row mt-1 gy-2">
    <div class="col-8 form-group borda_de_linha">
        <label for="fornecedor_id">Fornecedor:</label>
        <b>{{ $requisicao->fornecedor ? $requisicao->fornecedor->nome : '' }}</b>
    </div>
    <div class="col-4 form-group borda_de_linha">
        <label for="">Status:</label>
        <b>{{ $requisicao->status }}</b><br>
    </div>
</div>
<div class="row mt-1 gy-2">
    <div class="col-6 form-group borda_de_linha">
        <label for="fornecedor_id">Email/Whatsapp Fornecedor:</label>
        <b>{{ $requisicao->fornecedor_email." ".$requisicao->fornecedor_whatsapp }}</b>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="data_previa_conclusao">Data Prévia Conclusão:</label>
        <b>{{ dataDbForm($requisicao->data_previa_conclusao) }}</b>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="Utilizada">Utilizada:</label>
        <b>{{ $requisicao->aceito_pelo_fornecedor ? 'Sim '.$dt_confirmacao : 'Não' }}</b>
    </div>
</div>
<div class="row mt-1 gy-2">
    <div class="col-3 form-group borda_de_linha">
        <label for="user_moderador_id">Solicitante:</label>
        <b>{{ $requisicao->criador->nome }}</b>
        <b>{{ $dt_criacao }}</b>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="user_moderador_id">Compra:</label>
        <b>{{ $nm_compra }}</b>
        <b>{{ $dt_compra }}</b>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="user_moderador_id">Validação:</label>
        <b>{{ $hist_val ? $hist_val->user->nome : '---' }}</b>
        <b>{{ $dt_validacao }}</b>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="user_liberador_id">Aprovação:</label>
        <b>{{ $hist_aprov ? $hist_aprov->user->nome : '---' }}</b>
        <b>{{ $dt_aprovacao }}</b>
    </div>
</div>
<div class="row mt-1 gy-2 borda_de_linha">
    <div class="col-3 form-group borda_de_linha">
        <label for="setor_id">Setor:</label>
        <b>{{ $requisicao->setor->nome }}</b>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="unidade_id">Unidade:</label>
        <b>{{ $requisicao->unidade->nome }}</b>
    </div>
    <div class="col-6 form-group borda_de_linha">
        <label for="unidade_id">Operação:</label>
        <b style='font-size: 12px !important'>{{ $requisicao->financeiros->first() ? $requisicao->financeiros->first()->operacao->descricao : '' }}</b>
    </div>
</div>
<div class="row mt-1 gy-2 borda_de_linha">
    <div class="col-12 form-group">
        <label for="motivo_pedido_compra">Item/Motivo:</label>
        <b>{{ $requisicao->motivo_pedido_compra }}</b>
    </div>
</div>
<div class="row mt-1 gy-2 borda_de_linha">
    <div class="col-12 form-group">
        <label for="justificativa">Justificativa:</label>
        <b>{{ $requisicao->justificativa }}</b>
    </div>
</div>
<div class="row mt-1 gy-2 align-items-end">
    <div class="col-6 form-group borda_de_linha">
        <label for="qtd_itens_pedido">Total Quantidade:</label>
        <b>{{ $requisicao->qtd_itens_pedido }}</b>
    </div>
    <div class="col-6 form-group borda_de_linha">
        <label for="subtotal_pedido">Subtotal Pedido:</label>
        <b>R$ {{ valorDbForm($requisicao->subtotal_pedido) }}</b>
    </div>
    <div class="col-4 form-group borda_de_linha">
        <label for="frete">Frete:</label>
        <b>R$ {{ valorDbForm($requisicao->frete) }}</b>
    </div>
    <div class="col-4 form-group borda_de_linha">
        <label for="outras_despesas">Outras Despesas:</label>
        <b>R$ {{ valorDbForm($requisicao->outras_despesas) }}</b>
    </div>
    <div class="col-4 form-group borda_de_linha">
        <label for="desconto">Desconto:</label>
        <b>R$ {{ valorDbForm($requisicao->desconto) }}</b>
    </div>
    <div class="col-6 form-group borda_de_linha">
        <label for="acrescimo">Acrescimo:</label>
        <b>R$ {{ valorDbForm($requisicao->acrescimo) }}</b>
    </div>
    <div class="col-6 form-group borda_de_linha">
        <label for="total_pedido">Total Pedido:</label>
        <b style="font-size: 12px !important">R$ {{ valorDbForm($requisicao->total_pedido) }}</b>
    </div>
</div>
<div class="d-flex justify-content-between mt-1 mb-1">
    <h5 class="card-title">Itens</h5>
</div>
<div class="table-responsive borda_de_linha">
    <table class="table table-sm">
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
<hr>
<div class="d-flex justify-content-between mt-1 mb-1">
    <h5 class="card-title">Financeiro</h5>
</div>
<div id="div_anexos">
    <div class="table-responsive borda_de_linha">
        <table class="table">
            <thead class="table-light">
                <th>Tipo</th>
                <th>Origem</th>
                <th>Descrição</th>
                <th>Operação</th>
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
                        <td>{{ $financeiro->operacao->descricao }}</td>
                        <td>{{ dataDbForm($financeiro->vencimento) }}</td>
                        <td>R$ {{ valorDbForm($financeiro->valor) }}</td>
                        <td>{{ $financeiro->doc }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-between mt-1 mb-1">
    <h5 class="card-title">Entregas</h5>
</div>
<div id="div_anexos">
    <div class="table-responsive borda_de_linha">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>Produto</th>
                    <th>Qtd Pedido</th>
                    <th>Entregue</th>
                    <th>Devolução</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requisicao->itens as $item)
                    @php
                    $saldo = $item->qtd_pedida - $item->qtd_entregue - $item->qtd_devolucao;
                    @endphp
                    <tr>
                        <td>{{ $item->item->nome }}</td>
                        <td>{{ $item->qtd_pedida }}</td>
                        <td>{{ $item->qtd_entregue ? $item->qtd_entregue : '0' }}</td>
                        <td>{{ $item->qtd_devolucao ? $item->qtd_devolucao : '0' }}</td>
                        <td>{{ $saldo }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-between mt-1 mb-1">
    <h5 class="card-title">Histórico</h5>
</div>
<div class="table-responsive borda_de_linha">
    <table class="table">
        <thead class="table-light">
            <tr>
                <th>Usuário/Status/Data</th>
                <th>Hostórico</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requisicao->historicos()->orderByDesc('created_at')->get() as $historico)
                @php
                $var = explode(' ', $historico->created_at);
                $dt_historico = dataDbForm($var[0])." ".$var[1];
                @endphp
                <tr>
                    <td>
                        {{ $historico->user->nome }}<br>
                        {{ $historico->status }}<br>
                        {{ $dt_historico }}
                    </td>
                    <td>{{ $historico->ds_historico }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
window.addEventListener('load', ()=>{
    print();
})

window.addEventListener('afterprint', ()=>{
    window.close();
})
</script>
@endsection
