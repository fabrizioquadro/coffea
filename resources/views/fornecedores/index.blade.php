@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Fornecedores</h4>
            <div class="">
                <!-- <a href="{{ route('fornecedores.adicionar') }}" class="btn btn-primary">Adicionar</a> -->
                <button type="button" class="btn btn-secondary" id="sincronizar_sisagil">Sincronizar Sisagil</button>
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
            <table class="tabela-index table" id="table-index">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Nome</th>
                        <th>Fantasia</th>
                        <th>Sisagil ID</th>
                        <th>CNPJ/CPF</th>
                        <th>Cidade/UF</th>
                        <th>Celular</th>
                        <th>Email</th>
                    </tr>
                </thead>
                @foreach($fornecedores as $fornecedor)
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" data-popper-placement="bottom-end">
                                    <!--
                                    <a class="dropdown-item waves-effect" href="{{ route('fornecedores.editar', $fornecedor->id) }}"><i class="mdi mdi-pencil-outline me-1"></i> Editar</a>
                                    <a class="dropdown-item waves-effect" href="{{ route('fornecedores.excluir', $fornecedor->id) }}"><i class="mdi mdi-trash-can-outline me-1"></i> Excluir</a>
                                    -->
                                    <a class="dropdown-item waves-effect" href="{{ route('fornecedores.visualizar', $fornecedor->id) }}"><i class="mdi mdi-eye me-1"></i> Visualizar</a>
                                </div>
                            </div>
                        </td>
                        <td>{{ $fornecedor->nome }}</td>
                        <td>{{ $fornecedor->fantasia }}</td>
                        <td>{{ $fornecedor->sisagil_id }}</td>
                        <td>{{ $fornecedor->cpf_cnpj }}</td>
                        <td>{{ $fornecedor->cidade ? $fornecedor->cidade : '' }}{{$fornecedor->uf ? '/'.$fornecedor->uf : ''}}</td>
                        <td>{{ $fornecedor->celular }}</td>
                        <td>{{ $fornecedor->email }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
<script>
window.addEventListener('load',()=>{
  $('#table-index').DataTable({
      pageLength: 10,
    "pageLength": 10,
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
    if(confirm('Tem certeza que deseja atualizar a sincronização com o sisagil na tabela de fornecedores?')){
        window.location.href = "{{ route('fornecedores.sincronizar_sisagil') }}";
    }
})

</script>
@endsection
