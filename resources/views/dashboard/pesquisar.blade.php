@extends('layout.admin')

@section('conteudo')
@php
$user = auth()->user();
@endphp
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">Resultado Pesquisa</h4>
        <form action="{{ route('pesquisar') }}" method="post">
            @csrf
            <div class="card card-border-shadow-primary mb-4">
                <div class="card-body">
                    <h6 class="card-title">Nova Pesquisa</h6>
                    <div class="row mt-2 gy-4 align-items-end">
                        <div class="col-md-8">
                            <div class="form-floating form-floating-outline">
                                <input required class="form-control" type="text" id="pesquisar" name="pesquisar"/>
                                <label for="pesquisar">Pesquisar:</label>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <button type="submit" class="btn btn-primary me-2">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <div class="table-responsive mt-3">
            <table class="tabela-index table" id="table-index">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Status</th>
                        <th>Data Requisição</th>
                        <th>Fornecedor</th>
                        <th>Solicitante</th>
                        <th>Item/Motivo</th>
                        <th>Justificativa</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requisicoes as $requisicao)
                        @php
                        $var = explode(' ', $requisicao->created_at);
                        $dt_requisicao = dataDbForm($var[0]);
                        @endphp
                        <tr>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu" data-popper-placement="bottom-end">
                                        @if($requisicao->status == "Pedido")
                                            <a class="dropdown-item waves-effect" href="{{ route('pedidos.acessar', $requisicao->id) }}"><i class="mdi mdi-eye me-1"></i> Acessar</a>
                                            @if($requisicao->status == "Pedido" && ($user->perfil->editar || $user->perfil->administrador))
                                                <a class="dropdown-item waves-effect" href="{{ route('pedidos.editar', $requisicao->id) }}"><i class="mdi mdi-pencil-outline me-1"></i> Editar</a>
                                            @endif
                                        @elseif($requisicao->status == "Pedido Cancelado" || $requisicao->status == "Compra Cancelada")
                                            <a class="dropdown-item waves-effect" href="{{ route('pedidos.acessar', $requisicao->id) }}"><i class="mdi mdi-eye me-1"></i> Acessar</a>
                                        @elseif($requisicao->status == "Pedido Compra" || $requisicao->status == "Retornado para Compra" || $requisicao->status == "Em Validação" || $requisicao->status == "Retornado para Validação" || $requisicao->status == "Em Autorização" || $requisicao->status == "Aguardando Token de Aprovação")
                                            <a class="dropdown-item waves-effect" href="{{ route('requisicoes.acessar', $requisicao->id) }}"><i class="mdi mdi-eye me-1"></i> Acessar</a>
                                            <a class="dropdown-item waves-effect" href="{{ route('requisicoes.editar', $requisicao->id) }}"><i class="mdi mdi-pencil-outline me-1"></i> Editar</a>
                                            <a class="dropdown-item waves-effect" href="{{ route('requisicoes.excluir', $requisicao->id) }}"><i class="mdi mdi-trash-can-outline me-1"></i> Excluir</a>
                                        @elseif($requisicao->status == "Compra Aprovada")
                                            @if($requisicao->aceito_pelo_fornecedor && ($user->perfil->administrador || $user->perfil->confirmar_recebimento))
                                                <a class="dropdown-item waves-effect" href="{{ route('compras.entregas', $requisicao->id) }}"><i class="mdi mdi-truck-delivery-outline me-1"></i> Entregas</a>
                                            @endif
                                            <a class="dropdown-item waves-effect" href="{{ route('compras.acessar', $requisicao->id) }}"><i class="mdi mdi-eye me-1"></i> Acessar</a>
                                            @if($user->perfil->administrador || $user->perfil->cancelar)
                                                <a class="dropdown-item waves-effect" href="{{ route('compras.cancelar', $requisicao->id) }}"><i class="mdi mdi-delete me-1"></i> Cancelar</a>
                                                <a class="dropdown-item waves-effect" href="{{ route('compras.retornar', $requisicao->id) }}"><i class="mdi mdi-reload me-1"></i> Retornar</a>
                                            @endif
                                            @if($requisicao->aceito_pelo_fornecedor && ($user->perfil->administrador || $user->perfil->moderar || $user->perfil->integrar_financeiro))
                                                <a class="dropdown-item waves-effect" href="{{ route('compras.integrar', $requisicao->id) }}"><i class="mdi mdi-link me-1"></i> Integrar</a>
                                            @endif
                                        @elseif($requisicao->status == "Compra Finalizada")
                                            <a class="dropdown-item waves-effect" href="{{ route('finalizados.acessar', $requisicao->id) }}"><i class="mdi mdi-eye me-1"></i> Acessar</a>
                                            @if($user->perfil->administrador || $user->perfil->integrar_financeiro)
                                                <a class="dropdown-item waves-effect" href="{{ route('finalizados.integrar', $requisicao->id) }}"><i class="mdi mdi-link me-1"></i> Integrar</a>
                                            @endif
                                            <a class="dropdown-item waves-effect" href="{{ route('finalizados.entregas', $requisicao->id) }}"><i class="mdi mdi-truck-delivery-outline me-1"></i> Entregas</a>
                                            @if($user->perfil->administrador || $user->perfil->cancelar)
                                                <a class="dropdown-item waves-effect" href="{{ route('compras.cancelar', $requisicao->id) }}"><i class="mdi mdi-delete me-1"></i> Cancelar</a>
                                                <a class="dropdown-item waves-effect" href="{{ route('compras.retornar', $requisicao->id) }}"><i class="mdi mdi-reload me-1"></i> Retornar</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $requisicao->id }}</td>
                            <td>{{ $requisicao->status }}</td>
                            <td>{{ $dt_requisicao }}</td>
                            <td>{{ $requisicao->fornecedor ? $requisicao->fornecedor->nome : '' }}</td>
                            <td>{{ $requisicao->criador->nome }}</td>
                            <td>{{ $requisicao->motivo_pedido_compra }}</td>
                            <td>{{ $requisicao->justificativa }}</td>
                            <td>R$ {{ valorDbForm($requisicao->total_pedido) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">

window.addEventListener('load',()=>{
    $('#table-index').DataTable({
      order: [[1, 'desc']],
      "language": {
              "sEmptyTable": "Nenhum registro encontrado",
        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
        "sInfoPostFix": "",
        "sInfoThousands": ".",
        "sLengthMenu": "_MENU_ resultados por página",
        "sLoadingRecords": "Carregando...",
        "sProcessing": "Processando...",
        "sZeroRecords": "Nenhum registro encontrado",
        "sSearch": "Pesquisar",
        "oPaginate": {
          "sNext": "Próximo",
          "sPrevious": "Anterior",
          "sFirst": "Primeiro",
          "sLast": "Último"
        },
        "oAria": {
          "sSortAscending": ": Ordenar colunas de forma ascendente",
          "sSortDescending": ": Ordenar colunas de forma descendente"
        }
      }
    });
});

</script>
@endsection
