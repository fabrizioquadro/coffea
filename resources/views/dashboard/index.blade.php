@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <h4 class="card-title">Dashboard</h4>
        <div class="row mt-3">
            <h6 class="card-title">Seus Pedidos</h6>
            <div class="col-sm-4 col-lg-2 col-md-2 mb-4">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-danger"><i class="mdi mdi-receipt-text-edit-outline mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6">{{ $nr_pedidos }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Pedidos Criados</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-2 col-md-2 mb-4">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-page-previous-outline mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6">{{ $nr_preparos }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Pedidos Orçado</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-2 col-md-2 mb-4">
                <div class="card card-border-shadow-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-alert-box-outline mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6">{{ $nr_validacao }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Pedidos Validação</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-2 col-md-2 mb-4">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-receipt-text-send-outline mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6">{{ $nr_autorizacao }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Pedidos Autorização</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-2 col-md-2 mb-4">
                <div class="card card-border-shadow-warning h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-warning"><i class="mdi mdi-truck-delivery-outline mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6">{{ $nr_aprovados }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Aguardando Entrega</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-2 col-md-2 mb-4">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-success"><i class="mdi mdi-check mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6">{{ $nr_finalizados }}</h4>
                        </div>
                        <p class="mb-0 text-heading">Pedidos Finalizados</p>
                    </div>
                </div>
            </div>
        </div>
        @if($user->perfil->preparar_compra && $preparar_compra_requisicoes->count() > 0)
            <div class="card card-border-shadow-primary mb-4">
                <div class="card-body">
                    <h6 class="card-title">Preparar Comprar e Enviar a Validação</h6>
                    <div class="table-responsive">
                        <table class="tabela-index table" id="table-preparar">
                            <thead class="table-light">
                                <tr>
                                    <th>Cod</th>
                                    <th>Data</th>
                                    <th>Solicitante</th>
                                    <th>Unidade</th>
                                    <th>Setor</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach($preparar_compra_requisicoes as $requisicao)
                                @php
                                $var = explode(' ', $requisicao->created_at);
                                $dt_criacao = dataDbForm($var[0]);
                                @endphp
                                <tr>
                                    <td>{{ $requisicao->id }}</td>
                                    <td>{{ $dt_criacao }}</td>
                                    <td>{{ $requisicao->criador->nome }}</td>
                                    <td>{{ $requisicao->unidade->nome }}</td>
                                    <td>{{ $requisicao->setor->nome }}</td>
                                    <td>{{ $requisicao->status }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu" data-popper-placement="bottom-end">
                                                @if($requisicao->status == "Pedido")
                                                    <a class="dropdown-item waves-effect" href="{{ route('pedidos.acessar', $requisicao->id) }}"><i class="mdi mdi-eye me-1"></i> Acessar</a>
                                                @endif
                                                @if($requisicao->status == "Pedido Compra")
                                                    <a class="dropdown-item waves-effect" href="{{ route('requisicoes.acessar', $requisicao->id) }}"><i class="mdi mdi-eye me-1"></i> Acessar</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        @endif
        @if($user->perfil->moderar && $validacao_requisicoes->count() > 0)
            <div class="card card-border-shadow-primary mb-4">
                <div class="card-body">
                    <h6 class="card-title">Em Validação</h6>
                    <div class="table-responsive">
                        <table class="tabela-index table" id="table-validacao">
                            <thead class="table-light">
                                <tr>
                                    <th></th>
                                    <th>Codigo</th>
                                    <th>Status</th>
                                    <th>Data Requisição</th>
                                    <th>Fornecedor</th>
                                    <th>Solicitante</th>
                                    <th>Moderador</th>
                                    <th>Liberador</th>
                                    <th>Simples Cotação</th>
                                    <th>Valor Total</th>
                                </tr>
                            </thead>
                            @foreach($validacao_requisicoes as $requisicao)
                                @php
                                $var = explode(' ', $requisicao->created_at);
                                $dt_criacao = dataDbForm($var[0]);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu" data-popper-placement="bottom-end">
                                                <a class="dropdown-item waves-effect" href="{{ route('requisicoes.acessar', $requisicao->id) }}"><i class="mdi mdi-eye me-1"></i> Acessar</a>
                                                <a class="dropdown-item waves-effect" href="{{ route('requisicoes.editar', $requisicao->id) }}"><i class="mdi mdi-pencil-outline me-1"></i> Editar</a>
                                                <a class="dropdown-item waves-effect" href="{{ route('requisicoes.excluir', $requisicao->id) }}"><i class="mdi mdi-trash-can-outline me-1"></i> Excluir</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $requisicao->id }}</td>
                                    <td>{{ $requisicao->status }}</td>
                                    <td>{{ $dt_criacao }}</td>
                                    <td>{{ $requisicao->fornecedor ? $requisicao->fornecedor->nome : "" }}</td>
                                    <td>{{ $requisicao->criador ? $requisicao->criador->nome : "" }}</td>
                                    <td>{{ $requisicao->moderador ? $requisicao->moderador->nome : "" }}</td>
                                    <td>{{ $requisicao->liberador ? $requisicao->liberador->nome : "" }}</td>
                                    <td>{{ $requisicao->simples_cotacao ? 'Sim' : 'Não' }}</td>
                                    <td>R$ {{ valorDbForm($requisicao->total_pedido) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        @endif
        @if($user->perfil->aprovar && $autorizacao_requisicoes->count() > 0)
            <div class="card card-border-shadow-primary mb-4">
                <div class="card-body">
                    <h6 class="card-title">Em Autorização</h6>
                    <div class="table-responsive">
                        <table class="tabela-index table" id="table-autorizacao">
                            <thead class="table-light">
                                <tr>
                                    <th></th>
                                    <th>Codigo</th>
                                    <th>Status</th>
                                    <th>Data Requisição</th>
                                    <th>Fornecedor</th>
                                    <th>Solicitante</th>
                                    <th>Moderador</th>
                                    <th>Liberador</th>
                                    <th>Simples Cotação</th>
                                    <th>Valor Total</th>
                                </tr>
                            </thead>
                            @foreach($autorizacao_requisicoes as $requisicao)
                                @php
                                $var = explode(' ', $requisicao->created_at);
                                $dt_criacao = dataDbForm($var[0]);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu" data-popper-placement="bottom-end">
                                                <a class="dropdown-item waves-effect" href="{{ route('requisicoes.acessar', $requisicao->id) }}"><i class="mdi mdi-eye me-1"></i> Acessar</a>
                                                <a class="dropdown-item waves-effect" href="{{ route('requisicoes.editar', $requisicao->id) }}"><i class="mdi mdi-pencil-outline me-1"></i> Editar</a>
                                                <a class="dropdown-item waves-effect" href="{{ route('requisicoes.excluir', $requisicao->id) }}"><i class="mdi mdi-trash-can-outline me-1"></i> Excluir</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $requisicao->id }}</td>
                                    <td>{{ $requisicao->status }}</td>
                                    <td>{{ $dt_criacao }}</td>
                                    <td>{{ $requisicao->fornecedor ? $requisicao->fornecedor->nome : "" }}</td>
                                    <td>{{ $requisicao->criador ? $requisicao->criador->nome : "" }}</td>
                                    <td>{{ $requisicao->moderador ? $requisicao->moderador->nome : "" }}</td>
                                    <td>{{ $requisicao->liberador ? $requisicao->liberador->nome : "" }}</td>
                                    <td>{{ $requisicao->simples_cotacao ? 'Sim' : 'Não' }}</td>
                                    <td>R$ {{ valorDbForm($requisicao->total_pedido) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<script>
window.addEventListener('load',()=>{
      $('#table-preparar').DataTable({
        order: [[0, 'desc']],
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

      $('#table-validacao').DataTable({
        order: [[0, 'desc']],
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

      $('#table-autorizacao').DataTable({
        order: [[0, 'desc']],
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
})

</script>
@endsection
