@extends('layout.imprimir')

@section('conteudo')
<style media="print">
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
</style>
@php
$data_manifestacao_fornecedor = "";
if($requisicao->data_manifestacao_fornecedor){
    $var = explode(' ', $requisicao->data_manifestacao_fornecedor);
    $data_manifestacao_fornecedor = dataDbForm($var[0])." ".$var[1];
}
@endphp
<div class="row align-items-end pt-3 pb-3 mb-2">
    <div class="col-4">
        <img src="/public/img/unidades/{{ $requisicao->unidade->logo }}" height="60px" alt='logo'>
    </div>
    <div class="col-8" align='center'>
        <h6>Unidade: <strong>{{ $requisicao->unidade->nome }}</strong></h6>
    </div>
</div>
<div class="d-flex justify-content-between">
    <h4 class="card-title">Pedido Compra</h4>
</div>
<hr>
<div class="row align-items-end mt-3 gy-2">
    <div class="col-3 form-group">
        <img src="{{ $qrcode }}" height="100px">
    </div>
    <div class="col-9 form-group">
        <h6>Requisição: <strong>{{ $requisicao->id }}</strong></h6>
        <label for="">Compra Autorizada por: <strong>{{ $requisicao->liberador->nome }}</strong> </label><br>
        <label for="">Link Verificação Fornecedor:</label>
        <a target='_blank' href="{{ $link }}" style="text-decoration: none; color: #696969">{{ $link }}</a>
    </div>
</div>
<div class="row mt-3 gy-2">
    <div class="col-3 form-group borda_de_linha">
        <label for="">Status:</label><br>
        <b>{{ $requisicao->status }}</b><br>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="">Aceite Fornecedor:</label><br>
        <b>{{ !$requisicao->qrcode()->aceite_fornecedor ? 'Não Informado' : $requisicao->qrcode()->aceite_fornecedor }}</b><br>
    </div>
    <div class="col-6 form-group borda_de_linha">
        <label for="">Data manifestação Fornecedor:</label><br>
        <b>{{ $data_manifestacao_fornecedor }}</b><br>
    </div>
</div>
<div class="row mt-2 gy-2">
    <div class="col-8 form-group borda_de_linha">
        <label for="fornecedor_id">Fornecedor:</label><br>
        <b>{{ $requisicao->fornecedor->nome }}</b>
    </div>
    <div class="col-4 form-group borda_de_linha">
        <label for="data_previa_conclusao">Prazo para Aceite:</label><br>
        <b>{{ dataDbForm($requisicao->data_previa_conclusao) }}</b>
    </div>
</div>
<hr>
<div class="d-flex justify-content-between mt-3 mb-3">
    <h5 class="card-title">Itens</h5>
</div>
<div class="table-responsive borda_de_linha">
    <table class="table table-sm">
        <thead class="table-light">
            <tr>
                <th>Item</th>
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
                    <td>{{ $item->qtd_pedida }}</td>
                    <td>R${{ valorDbForm($item->valor_unid) }}</td>
                    <td>R${{ valorDbForm($item->valor_total_pedido) }}</td>
                    <td>{{ dataDbForm($item->data_previsao_entrega) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="row mt-2 gy-2 align-items-end">
    <div class="col-3 form-group borda_de_linha">
        <label for="frete">Frete:</label><br>
        <b>R$ {{ valorDbForm($requisicao->frete) }}</b>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="outras_despesas">Outras Despesas:</label><br>
        <b>R$ {{ valorDbForm($requisicao->outras_despesas) }}</b>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="desconto">Desconto:</label><br>
        <b>R$ {{ valorDbForm($requisicao->desconto) }}</b>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="acrescimo">Acrescimo:</label><br>
        <b>R$ {{ valorDbForm($requisicao->acrescimo) }}</b>
    </div>
    <div class="col-4 form-group borda_de_linha">
        <label for="qtd_itens_pedido">Total Quantidade:</label><br>
        <b>{{ $requisicao->qtd_itens_pedido }}</b>
    </div>
    <div class="col-4 form-group borda_de_linha">
        <label for="subtotal_pedido">Subtotal Pedido:</label><br>
        <b>R$ {{ valorDbForm($requisicao->subtotal_pedido) }}</b>
    </div>
    <div class="col-4 form-group borda_de_linha">
        <label for="total_pedido">Total Pedido:</label><br>
        <b>R$ {{ valorDbForm($requisicao->total_pedido) }}</b>
    </div>
</div>
<hr>
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
<script>
window.addEventListener('load', ()=>{
    print();
})

window.addEventListener('afterprint', ()=>{
    window.close();
})
</script>
@endsection
