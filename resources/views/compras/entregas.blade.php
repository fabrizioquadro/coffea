@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Entregas</h4>
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                    Ações
                    <i class="mdi mdi-dots-vertical"></i>
                </button>
                <div class="dropdown-menu" data-popper-placement="bottom-end">
                    <button class="dropdown-item waves-effect" id="botao_entrega_total">Entrega Total</button>
                    <button class="dropdown-item waves-effect" id="botao_entrega_parcial">Entrega Parcial</button>
                    <button class="dropdown-item waves-effect" id="botao_entrega_cancelamento">Cancelamento/Devolução</button>
                </div>
            </div>
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
<form id="formulario" action="{{ route('compras.entregas.set') }}" method="post">
    @csrf
    <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
    <input type="hidden" name="tipo_entrega" id="tipo_entrega">
</form>

<div class="modal fade" id="modal_entrega_parcial" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" action="{{ route('compras.entregas.set') }}" method="post">
            @csrf
            <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
            <input type="hidden" name="tipo_entrega" value="parcial">
            <div class="modal-header">
                <h5 class="modal-title" id="backDropModalTitle">Entrega Parcial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th>Qtd Pedido</th>
                                <th>Entregue</th>
                                <th>Devolução</th>
                                <th>Entrega</th>
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
                                    <td><input class="form-control" type="number" id="qtd_entregue_{{ $item->id }}" name="qtd_entregue_{{ $item->id }}" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any"/></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <textarea class="form-control h-px-100" id="justificativa" name="justificativa"></textarea>
                            <label for="justificativa">Justificativa:</label>
                          </div>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <button class="btn btn-primary" type="submit">Adicionar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_cancelamento" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" action="{{ route('compras.entregas.set') }}" method="post">
            @csrf
            <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
            <input type="hidden" name="tipo_entrega" value="cancelamento">
            <div class="modal-header">
                <h5 class="modal-title" id="backDropModalTitle">Cancelamento/Devolução</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th>Qtd Pedido</th>
                                <th>Entregue</th>
                                <th>Devolução</th>
                                <th>Devolução</th>
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
                                    <td><input class="form-control" type="number" id="qtd_cancelamento_{{ $item->id }}" name="qtd_cancelamento_{{ $item->id }}" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any"/></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <textarea class="form-control h-px-100" id="justificativa" name="justificativa"></textarea>
                            <label for="justificativa">Justificativa:</label>
                          </div>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <button class="btn btn-primary" type="submit">Adicionar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
var modalParcial;
var modalCancelamento;

document.getElementById('botao_entrega_total').addEventListener('click', ()=>{
    if(confirm('Tem certeza que deseja fazer a entrega total dos itens restante nesse pedido?')){
        document.getElementById('tipo_entrega').value = 'total';
        document.getElementById('formulario').submit();
    }
})

document.getElementById('botao_entrega_parcial').addEventListener('click', ()=>{
    modalParcial = new bootstrap.Modal(document.getElementById('modal_entrega_parcial'));
    modalParcial.show();
})

document.getElementById('botao_entrega_cancelamento').addEventListener('click', ()=>{
    modalCancelamento = new bootstrap.Modal(document.getElementById('modal_cancelamento'));
    modalCancelamento.show();
})




</script>
@endsection
