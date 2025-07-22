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
            <h4 class="card-title">Acessar Pedido</h4>
        </div>
        <hr>
        @if($requisicao->status == "Pedido")
            @if($user->perfil->administrador || $user->perfil->preparar_compra || $user->perfil->cancelar)
                <form action="{{ route('pedidos.preparar_compra') }}" method="post">
                    @csrf
                    <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
                    <div class="row mt-2 gy-2">
                        <div class="col-md-12">
                            @if($user->perfil->administrador || $user->perfil->preparar_compra)
                                <button class="btn btn-primary" name="preparar_compra" type="submit" value='true'>Prepara Compra</button>
                            @endif
                            @if($user->perfil->administrador || $user->perfil->cancelar)
                                <button class="btn btn-danger" name="cancelar_compra" type="submit" value='true'>Cancelar Pedido</button>
                            @endif
                        </div>
                    </div>
                </form>
                <hr>
            @endif
        @endif
        <div class="row mt-2 gy-2">
            <div class="col-md-6 form-group borda_de_linha">
                <label for="">Status:</label><br>
                <b>{{ $requisicao->status }}</b>
            </div>
            <div class="col-md-6 form-group borda_de_linha">
                <label for="">Simples Cotação:</label><br>
                <b>{{ $requisicao->simples_cotacao ? "Sim" : 'Não' }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2">
            <div class="col-md-4 form-group borda_de_linha">
                <label for="user_moderador_id">Solicitante:</label><br>
                <b>{{ $requisicao->criador->nome }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="setor_id">Setor:</label><br>
                <b>{{ $requisicao->setor->nome }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="unidade_id">Unidade:</label><br>
                <b>{{ $requisicao->unidade->nome }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2 borda_de_linha">
            <div class="col-md-12 form-group">
                <label for="motivo_pedido_compra">Motivo de Uso:</label><br>
                <b>{{ $requisicao->motivo_pedido_compra }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2 borda_de_linha">
            <div class="col-md-12 form-group">
                <label for="justificativa">Justificativa:</label><br>
                <b>{{ $requisicao->justificativa }}</b>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between mt-3 mb-3">
            <h5 class="card-title">Itens</h5>
        </div>
        <div class="table-responsive borda_de_linha">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th>Qtd</th>
                        <th>Obs</th>
                        <th>Patrimonio</th>
                    </tr>
                </thead>
                <tbody id="tabela_items">
                    @foreach($requisicao->itens as $item)
                        <tr id="linha_item_cadastrada_{{ $item->id }}">
                            <td>{{ $item->item->nome }}</td>
                            <td>{{ $item->qtd_pedida }}</td>
                            <td>{{ $item->obs }}</td>
                            <td>{{ $item->lancar_patrimonio ? 'Sim' : 'Não' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <div class="row mt-2 gy-2 align-items-end">
            <div class="col-md-6 form-group borda_de_linha">
                <label for="qtd_itens_pedido">Total Quantidade:</label><br>
                <b>{{ $requisicao->qtd_itens_pedido }}</b>
            </div>
        </div>
        <a href="{{ route('pedidos.editar', $requisicao->id) }}" class="btn btn-warning btn-sm mt-3">Editar</a>
    </div>
</div>

@endsection
