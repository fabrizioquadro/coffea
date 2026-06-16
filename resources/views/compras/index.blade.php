@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Compras</h4>
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
                        <th>Entregas</th>
                        <th>Data Requisição</th>
                        <th>Fornecedor</th>
                        <th>Utilizada</th>
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

                        if($requisicao->aceito_pelo_fornecedor){
                            $var = explode(' ', $requisicao->data_manifestacao_fornecedor);
                            $data_manifestacao_fornecedor = dataDbForm($var[0]);
                        }
                        else{
                            $data_manifestacao_fornecedor = '';
                        }
                        @endphp
                        <tr>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu" data-popper-placement="bottom-end">
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
                                    </div>
                                </div>
                            </td>
                            <td>{{ $requisicao->id }}</td>
                            <td>{{ $requisicao->status }}</td>
                            <td>{{ \App\Http\Controllers\CompraController::get_st_entrega($requisicao) }}</td>
                            <td>{{ $dt_criacao }}</td>
                            <td>{{ $requisicao->fornecedor->nome }}</td>
                            <td>{{ $requisicao->aceito_pelo_fornecedor ? 'Sim' : 'Não' }}</td>
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

</script>
@endsection
