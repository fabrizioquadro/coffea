@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Perfis</h4>
            <a href="{{ route('perfis.adicionar') }}" class="btn btn-primary">Adicionar</a>
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
            <table class="tabela-index table" id="table-index">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Administrador</th>
                        <th>Criar</th>
                        <th>Preparar Compra</th>
                        <th>Duplicar Pedido</th>
                        <th>Moderar</th>
                        <th>Aprovar</th>
                        <th>Confirmar Recebimento</th>
                        <th>Alterar Quantidade</th>
                        <th>Editar</th>
                        <th>Corrigir</th>
                        <th>Cancelar</th>
                        <th>Acompanhar</th>
                        <th>Data Cadastro</th>
                        <th>Usuário Cadastro</th>
                        <th>Data Alteração</th>
                        <th>Usuário Alteração</th>
                    </tr>
                </thead>
                @foreach($perfis as $perfil)
                    @php
                    $var = explode(' ', $perfil->created_at);
                    $dt_cad = dataDbForm($var[0]);
                    $var = explode(' ', $perfil->updated_at);
                    $dt_update = dataDbForm($var[0]);
                    @endphp
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" data-popper-placement="bottom-end">
                                    <a class="dropdown-item waves-effect" href="{{ route('perfis.editar', $perfil->id) }}"><i class="mdi mdi-pencil-outline me-1"></i> Editar</a>
                                    <a class="dropdown-item waves-effect" href="{{ route('perfis.excluir', $perfil->id) }}"><i class="mdi mdi-trash-can-outline me-1"></i> Excluir</a>
                                </div>
                            </div>
                        </td>
                        <td>{{ $perfil->descricao }}</td>
                        <td>{{ $perfil->status }}</td>
                        <td>{{ $perfil->administrador ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->criar ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->preparar_compra ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->duplicar_pedido_compra ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->moderar ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->aprovar ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->confirmar_recebimento ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->alterar_qtd_recebimento ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->editar ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->corrigir ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->cancelar ? 'Sim' : 'Não' }}</td>
                        <td>{{ $perfil->acompanhar ? 'Sim' : 'Não' }}</td>
                        <td>{{ $dt_cad }}</td>
                        <td>{{ $perfil->user_cad ? $perfil->user_cad->nome : '' }}</td>
                        <td>{{ $dt_update }}</td>
                        <td>{{ $perfil->user_update ? $perfil->user_update->nome : '' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
<script>
window.addEventListener('load',()=>{
  $('#table-index').DataTable({
    order: [[1, 'asc']],
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
