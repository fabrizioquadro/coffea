@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Relatório Financeiro - Gerar</h4>
            <button type="button" class="btn btn-primary" id="botao_imprimir">Imprimir</button>
        </div>
        <hr>
        <div id="div_dados">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="">Operação:</label>
                    <b>{{ $filtro['operacao'] }}</b>
                </div>
                <div class="col-md-6 form-group">
                    <label for="">Unidade:</label>
                    <b>{{ $filtro['unidade'] }}</b>
                </div>
                <div class="col-md-6 form-group">
                    <label for="">Situação:</label>
                    <b>{{ $filtro['status'] }}</b>
                </div>
                <div class="col-md-6 form-group">
                    <label for="">Cred/Deb:</label>
                    <b>{{ $filtro['cred_deb'] }}</b>
                </div>
                <div class="col-md-6 form-group">
                    <label for="">Origem:</label>
                    <b>{{ $filtro['origem'] }}</b>
                </div>
                <div class="col-md-4 form-group">
                    <label for="">Integrado:</label>
                    <b>{{ $filtro['integrado'] }}</b>
                </div>
                <div class="col-md-4 form-group">
                    <label for="">Início:</label>
                    <b>{{ $filtro['dt_inc'] }}</b>
                </div>
                <div class="col-md-4 form-group">
                    <label for="">Fim:</label>
                    <b>{{ $filtro['dt_fn'] }}</b>
                </div>
                <div class="col-md-12 form-group">
                    <label for="">Emissão:</label>
                    <b>{{ date('d/m/Y H:i:s')." - Usuário: ".$user->nome." - ".count($financeiros)." ocorrências encontradas" }}</b>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="tabela-index table" id="table-index">
                    <thead class="table-light">
                        <tr>
                            <td colspan="10">Relatório Financeiro</td>
                        </tr>
                        <tr>
                            <th>Id</th>
                            <th>Operação</th>
                            <th>Unidade</th>
                            <th>Conta Pagamento</th>
                            <th>Cred/Deb</th>
                            <th>Origem</th>
                            <th>Descrição</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Doc</th>
                            <th>Obs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($financeiros as $financeiro)
                            @php
                            $total_valor += $financeiro->valor;
                            @endphp
                            <tr>
                                <td>{{ $financeiro->id }}</td>
                                <td>{{ $financeiro->operacao->descricao }}</td>
                                <td>{{ $financeiro->requisicao->unidade->nome }}</td>
                                <td>{{ $financeiro->conta_pagamento->descricao }}</td>
                                <td>{{ $financeiro->cred_deb }}</td>
                                <td>{{ $financeiro->origem }}</td>
                                <td>{{ $financeiro->descricao }}</td>
                                <td>{{ dataDbForm($financeiro->vencimento) }}</td>
                                <td>R$ {{ valorDbForm($financeiro->valor) }}</td>
                                <td>{{ $financeiro->doc }}</td>
                                <td>{{ $financeiro->obs }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8"> <b>TOTAL</b> </td>
                            <td> <b>R$ {{ valorDbForm($total_valor) }}</b> </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<form id="formulario" target="_blank" action="{{ route('relatorios.imprimir') }}" method="post">
    @csrf
    <input type="hidden" name="dados" id='dados'>
</form>
<script>
document.getElementById('botao_imprimir').addEventListener('click', ()=>{
    dados = document.getElementById('div_dados').innerHTML;
    document.getElementById('dados').value = dados;
    document.getElementById('formulario').submit();
});
</script>
@endsection
