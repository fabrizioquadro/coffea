@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Integração Sisagil Financeiro</h4>
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
                        <th></th>
                        <th>Data</th>
                        <th>Conta</th>
                        <th>Cred/Deb</th>
                        <th>Tipo</th>
                        <th>Origem</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Integrado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requisicao->financeiros()->orderBy('vencimento')->get() as $financeiro)
                        <tr>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow show" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu" data-popper-placement="bottom-end">
                                        <a target='_blank' class="dropdown-item waves-effect" href="{{ route('compras.integrar.get_parcela_sisagil', $financeiro->id) }}"><i class="mdi mdi-target me-1"></i> Verificar Sisagil</a>
                                    </div>
                                </div>
                            </td>
                            <td>{{ dataDbForm($financeiro->vencimento) }}</td>
                            <td>{{ $financeiro->conta_pagamento->descricao }}</td>
                            <td>{{ $financeiro->cred_deb }}</td>
                            <td>{{ $view_tipo_pagamento[$financeiro->tipo_pagamento] }}</td>
                            <td>{{ $financeiro->origem }}</td>
                            <td>{{ $financeiro->descricao }}</td>
                            <td>R$ {{ valorDbForm($financeiro->valor) }}</td>
                            <td>{{ $financeiro->sisagil_id_retorno }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
