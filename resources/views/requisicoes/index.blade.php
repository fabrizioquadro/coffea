@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Requisições</h4>
            {{--
            @if($user->perfil->administrador || $user->perfil->criar)
                <a href="{{ route('requisicoes.adicionar') }}" class="btn btn-primary">Adicionar</a>
            @endif
            --}}
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
                        <th>Codigo</th>
                        <th>Status</th>
                        <th>Data Requisição</th>
                        <th>Fornecedor</th>
                        <th>Solicitante</th>
                        <th>Item/Motivo</th>
                        <th>Justificativa</th>
                        <th>Valor Total</th>
                        <th style='display:none'></th>
                    </tr>
                </thead>
                @foreach($requisicoes as $requisicao)
                    @if($user->unidades()->where('unidade_id', $requisicao->unidade_id)->count() > 0 && $user->setores()->where('setor_id', $requisicao->setor_id)->count() > 0)
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
                            <td>{{ $requisicao->motivo_pedido_compra }}</td>
                            <td>{{ $requisicao->justificativa }}</td>
                            <td>R$ {{ valorDbForm($requisicao->total_pedido) }}</td>
                            <td style="display:none">{{ $requisicao->motivo_pedido_compra." ".$requisicao->justificativa }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
</div>
<script>
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
})

</script>
@endsection
