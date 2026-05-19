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
    .span_qrCode{
        font-size: 8px !important;
        font-weight: 900 !important;
        width: 50% !important;
    }
</style>
@php
$data_manifestacao_fornecedor = "";
if($requisicao->data_manifestacao_fornecedor){
    $var = explode(' ', $requisicao->data_manifestacao_fornecedor);
    $data_manifestacao_fornecedor = dataDbForm($var[0])." ".$var[1];
}
$var = explode(' ',$requisicao->created_at);
$criacao = dataDbForm($var[0]);
$portador = $requisicao->portador ? $requisicao->portador : '____________________________________';
@endphp
<div class="row pt-3 pb-3 mb-2">
    <div class="col-4" style="border: 1px solid #cdcdcd; border-radius: 10px; height: 100%" align='center'>
        <img src="/public/img/unidades/{{ $requisicao->unidade->logo }}" class="img-fluid" alt='logo'>
        <h6 class="mt-3">Unidade: <strong>{{ $requisicao->unidade->nome }}</strong></h6>
    </div>
    <div class="col-3" align='center'>
        <img src="{{ $qrcode }}" height="100px"><br>
        <span class="span_qrCode">Escanear o QR Code para validar a Requisição</span>
    </div>
    <div class="col-5">
        <div class="row">
            <div class="col-8" align='center'>
                <span style="height: 40px !important" class="mdi mdi-information-slab-circle-outline"></span>
                <p>Para que a requisição tenha valor é necessário ler o QR Code ou acessar o link e clicar em Aceitar</p>
            </div>
            <div class="col-4">
                <h6>Requisição:</h6>
                <h1><strong>{{ $requisicao->id }}</strong></h1>
                <p>Autorizado por: <strong>{{ $requisicao->liberador->nome }}</strong> </p>
            </div>
        </div>
    </div>
    <div class="col-12 mt-3" align='right'>
        <label for="">Link Verificação Fornecedor:</label><br>
        <a target='_blank' href="{{ $link }}" style="text-decoration: none; color: #696969">{{ $link }}</a>
    </div>
</div>
<hr>
<p>
    Queiram fornecer Sr.(a) {{ $portador }} as seguintes mercadorias descriminadas.
</p>
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
<div class="row mt-3 gy-2">
    <div class="col-3 form-group borda_de_linha">
        <label for="">Criado em:</label><br>
        <b>{{ $criacao }}</b><br>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="">Data Impressão:</label><br>
        <b>{{ date('d/m/Y') }}</b><br>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="">Moderado por:</label><br>
        <b>{{ $requisicao->moderador->nome }}</b><br>
    </div>
    <div class="col-3 form-group borda_de_linha">
        <label for="">Autorizado por:</label><br>
        <b>{{ $requisicao->liberador->nome }}</b><br>
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
<div class="d-flex justify-content-between mt-3 mb-3">
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
<script>
window.addEventListener('load', ()=>{
    print();
})

window.addEventListener('afterprint', ()=>{
    window.close();
})
</script>
@endsection
