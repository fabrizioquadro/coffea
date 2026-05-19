@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Relatório Requisição - Gerar</h4>
            <button type="button" class="btn btn-primary" id="botao_imprimir">Imprimir</button>
        </div>
        <hr>
        <div id="div_dados">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="">Fornecedor:</label>
                    <b>{{ $filtro['fornecedor'] }}</b>
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
                    <label for="">Data Inicial:</label>
                    <b>{{ $filtro['dt_inc'] }}</b>
                </div>
                <div class="col-md-6 form-group">
                    <label for="">Data Final:</label>
                    <b>{{ $filtro['dt_fn'] }}</b>
                </div>
                <div class="col-md-12 form-group">
                    <label for="">Emissão:</label>
                    <b>{{ date('d/m/Y H:i:s')." - Usuário: ".$user->nome." - ".count($array_requisicoes)." ocorrências encontradas" }}</b>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <td colspan="8">Relatório de Requisições</td>
                        </tr>
                        <tr>
                            <th style="padding: 2px !important">Código</th>
                            <th style="padding: 2px !important">Status</th>
                            <th style="padding: 2px !important">Data Requisição</th>
                            <th style="padding: 2px !important">Fornecedor</th>
                            <th style="padding: 2px !important">Unidade</th>
                            <th style="padding: 2px !important">Solicitante</th>
                            <th style="padding: 2px !important">Item/Motivo</th>
                            <th style="padding: 2px !important">Justificativa</th>
                            <th style="padding: 2px !important">Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($array_requisicoes as $requisicao)
                            @php
                            $var = explode(' ', $requisicao->created_at);
                            $dt_requisicao = dataDbForm($var[0]);
                            $total_pedidos += $requisicao->total_pedido;
                            @endphp
                            <tr>
                                <td>{{ $requisicao->id }}</td>
                                <td>{{ $requisicao->status }}</td>
                                <td>{{ $dt_requisicao }}</td>
                                <td>{{ $requisicao->fornecedor ? $requisicao->fornecedor->nome : '' }}</td>
                                <td>{{ $requisicao->unidade ? $requisicao->unidade->nome : '' }}</td>
                                <td>{{ $requisicao->criador->nome }}</td>
                                <td>{{ $requisicao->motivo_pedido_compra }}</td>
                                <td>{{ $requisicao->justificativa }}</td>
                                <td>R$ {{ valorDbForm($requisicao->total_pedido) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tbody>
                        <tr>
                            <td colspan="8"> <b>TOTAL</b> </td>
                            <td> <b>R$ {{ valorDbForm($total_pedidos) }}</b> </td>
                        </tr>
                    </tbody>
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
