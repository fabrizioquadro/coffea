@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Entregas - Cod: {{ $requisicao->id }}</h4>
        </div>
        @if($mensagem = Session::get('mensagem'))
            <div class="alert alert-success alert-dismissible mt-3" role="alert">
                {{ $mensagem }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($mensagem = Session::get('mensagem_erro'))
            <div class="alert alert-danger alert-dismissible mt-3" role="alert">
                {{ $mensagem }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <hr>
        <div class="table-responsive">
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
</div>
@endsection
