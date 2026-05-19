@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Operações</h4>
            {{--<a href="{{ route('operacoes.adicionar') }}" class="btn btn-primary">Adicionar</a>--}}
            <button type="button" class="btn btn-secondary" id="sincronizar_sisagil">Sincronizar Sisagil</button>
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
                        <th>Codigo Sisagil</th>
                        <th>Status</th>
                        <th>Padrão Cancelamento</th>
                        <th>Unidade</th>
                    </tr>
                </thead>
                @foreach($operacoes as $operacao)
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" data-popper-placement="bottom-end">
                                    <a class="dropdown-item waves-effect" href="{{ route('operacoes.editar', $operacao->id) }}"><i class="mdi mdi-pencil-outline me-1"></i> Editar</a>
                                    <a class="dropdown-item waves-effect" href="{{ route('operacoes.excluir', $operacao->id) }}"><i class="mdi mdi-trash-can-outline me-1"></i> Excluir</a>
                                </div>
                            </div>
                        </td>
                        <td>{{ $operacao->descricao }}</td>
                        <td>{{ $operacao->sisagil_id }}</td>
                        <td>{{ $operacao->status }}</td>
                        <td>{{ $operacao->operacao_padrao_cancelamento }}</td>
                        <td>{{ $operacao->unidade ? $operacao->unidade->nome : '' }}</td>
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

document.getElementById('sincronizar_sisagil').addEventListener('click', ()=>{
    if(confirm('Tem certeza que deseja atualizar a sincronização com o sisagil na tabela de operações?')){
        window.location.href = "{{ route('operacoes.sincronizar_sisagil') }}";
    }
})

</script>
@endsection
