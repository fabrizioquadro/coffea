@extends('layout.admin')

@section('conteudo')

<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Unidades</h4>
            <a href="{{ route('unidades.adicionar') }}" class="btn btn-primary">Adicionar</a>
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
                        <th></th>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Restrita</th>
                    </tr>
                </thead>
                @foreach($unidades as $unidade)
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" data-popper-placement="bottom-end">
                                    <a class="dropdown-item waves-effect" href="{{ route('unidades.editar', $unidade->id) }}"><i class="mdi mdi-pencil-outline me-1"></i> Editar</a>
                                    <a class="dropdown-item waves-effect" href="{{ route('unidades.excluir', $unidade->id) }}"><i class="mdi mdi-trash-can-outline me-1"></i> Excluir</a>
                                    <a class="dropdown-item waves-effect" href="{{ route('unidades.visualizar', $unidade->id) }}"><i class="mdi mdi-eye me-1"></i> Visualizar</a>
                                    <a class="dropdown-item waves-effect" href="{{ route('unidades.testar_token', $unidade->id) }}" target='_blank'><i class="mdi mdi-link me-1"></i> Testar Api</a>
                                </div>
                            </div>
                        </td>
                        <td><img src='/public/img/unidades/{{ $unidade->logo."?".date("his") }}' style='height:40px; border-radius: 20px' alt='logo'></td>
                        <td>{{ $unidade->nome }}</td>
                        <td>{{ $unidade->status }}</td>
                        <td>{{ $unidade->restrita }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
<script>
window.addEventListener('load',()=>{
  $('#table-index').DataTable({
      pageLength: 50,
    "pageLength": 50,
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
