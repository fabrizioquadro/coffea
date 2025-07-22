@extends('layout.admin')

@section('conteudo')
<style media="screen">
    .borda_de_linha{
        border: 1px solid #dcdcdc;
        border-radius: 10px;
    }
</style>
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Acessar Requisição</h4>
        </div>
        <hr>
        @if($mensagem = Session::get('mensagem'))
            <div class="alert alert-success alert-dismissible mt-3 mb-2" role="alert">
                {{ $mensagem }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($mensagem = Session::get('mensagem_erro'))
            <div class="alert alert-danger alert-dismissible mt-3 mb-2" role="alert">
                {{ $mensagem }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($requisicao->mensagem)
            <div class="row gy-2">
                <div class="col-md-12 form-group">
                    <label for="">Mensagem Anexa:</label><br>
                    <b>{{ $requisicao->mensagem }}</b>
                </div>
            </div>
            <hr>
        @endif
        @if(!$requisicao->simples_cotacao)
            @if($requisicao->status == "Pedido Compra" || $requisicao->status == "Retornado para Compra")
                @if($user->perfil->administrador || $user->perfil->preparar_compra)
                    @if($requisicao->financeiros()->sum('valor') == 0 || $requisicao->financeiros()->sum('valor') != $requisicao->total_pedido)
                        <div class="alert alert-danger" role="alert">Total do Financeiro não confere com o Total do Pedido ou igual a zero!</div>
                        <a href="{{ route('requisicoes.editar', $requisicao->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    @else
                        <form action="{{ route('requisicoes.enviar_para_validacao') }}" method="post">
                            @csrf
                            <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
                            <div class="row gy-2">
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control h-px-100" id="mensagem" name="mensagem"></textarea>
                                        <label for="mensagem">Mensagem:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2 gy-2">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" name="enviar_para_validacao" type="submit" value='true'>Enviar para Validação</button>
                                    <button class="btn btn-sm btn-danger" id="botao_cancelar_requisicao" type="button">Cancelar Requisição</button>
                                    <a href="{{ route('requisicoes.editar', $requisicao->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                </div>
                            </div>
                            <hr>
                        </form>
                    @endif
                @endif
            @endif
            @if($requisicao->status == "Em Validação" || $requisicao->status == "Retornado para Validação")
                @if($user->perfil->administrador || $user->id == $requisicao->user_moderador_id)
                    {{-- se entrar aqui pode ser moderado ou rretornado ao solicitante --}}
                    <form action="{{ route('requisicoes.enviar_para_autorizacao') }}" method="post">
                        @csrf
                        <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
                        <div class="row gy-2">
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control h-px-100" id="mensagem" name="mensagem"></textarea>
                                    <label for="mensagem">Mensagem:</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 gy-2">
                            <div class="col-md-12">
                                <button class="btn btn-sm btn-primary" name="enviar_para_autorizacao" type="submit" value='true'>Enviar para Autorização</button>
                                <button class="btn btn-sm btn-secondary" id="botao_retornar_compra" type="button">Retornar para Compra</button>
                                <button class="btn btn-sm btn-danger" id="botao_cancelar_requisicao" type="button">Cancelar Requisição</button>
                                <a href="{{ route('requisicoes.editar', $requisicao->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            </div>
                        </div>
                    </form>
                    <script>
                    document.getElementById('botao_retornar_compra').addEventListener('click', ()=>{
                        if(confirm('Tem certeza que deseja retornar para análise de compras?')){
                            mensagem = document.getElementById('mensagem').value;
                            window.location.href = "{{ route('requisicoes.retornar_para_compra', $requisicao) }}?mensagem=" + mensagem;
                        }
                    })
                    </script>
                @endif
            @endif
            @if($requisicao->status == "Em Autorização")
                @if($user->perfil->administrador || $user->id == $requisicao->user_liberador_id)
                    {{-- se entrar aqui pode ser moderado ou rretornado ao solicitante --}}
                    <form action="{{ route('requisicoes.autorizar_compra') }}" method="post">
                        @csrf
                        <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
                        <div class="row gy-2">
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control h-px-100" id="mensagem" name="mensagem"></textarea>
                                    <label for="mensagem">Mensagem:</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 gy-2">
                            <div class="col-md-12">
                                <button class="btn btn-sm btn-primary" name="autorizar_compra" type="submit" value='true'>Autorizar Compra</button>
                                <button class="btn btn-sm btn-secondary" id="botao_retornar_validacao" type="button">Retornar para Validação</button>
                                <button class="btn btn-sm btn-danger" id="botao_cancelar_requisicao" type="button">Cancelar Requisição</button>
                                <a href="{{ route('requisicoes.editar', $requisicao->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            </div>
                        </div>
                    </form>
                    <script>
                    document.getElementById('botao_retornar_validacao').addEventListener('click', ()=>{
                        if(confirm('Tem certeza que deseja retornar para análise da validação?')){
                            mensagem = document.getElementById('mensagem').value;
                            window.location.href = "{{ route('requisicoes.retornar_para_validacao', $requisicao) }}?mensagem=" + mensagem;
                        }
                    })
                    </script>
                @endif
            @endif
            @if($requisicao->status == "Aguardando Token de Aprovação")
                @if($user->perfil->administrador || $user->id == $requisicao->user_liberador_id)
                    {{-- se entrar aqui pode ser moderado ou rretornado ao solicitante --}}
                    <form action="{{ route('requisicoes.ativar_compra') }}" method="post">
                        @csrf
                        <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
                        <div class="row align-items-end gy-2">
                            <div class="col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="codigo" name="codigo"/>
                                    <label for="codigo">Código:</label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <button class="btn btn-primary" name="autorizar_compra" type="submit" value='true'>Ativar Compra</button>
                                <button class="btn btn-secondary" name="gerar_novo_codigo" type="submit" value="true">Gerar Novo Código</button>
                            </div>
                        </div>
                    </form>
                    <script>
                    document.getElementById('botao_retornar_validacao').addEventListener('click', ()=>{
                        if(confirm('Tem certeza que deseja retornar para análise da validação?')){
                            mensagem = document.getElementById('mensagem').value;
                            window.location.href = "{{ route('requisicoes.retornar_para_validacao', $requisicao) }}?mensagem=" + mensagem;
                        }
                    })
                    </script>
                @endif
            @endif
        @else
            <a href="{{ route('requisicoes.editar', $requisicao->id) }}" class="btn btn-sm btn-warning">Editar</a>
        @endif

        <div class="row mt-3 gy-2">
            <div class="col-md-6 form-group borda_de_linha">
                <label for="">Status:</label><br>
                <b>{{ $requisicao->status }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2">
            <div class="col-md-4 form-group borda_de_linha">
                <label for="fornecedor_id">Fornecedor:</label><br>
                <b>{{ $requisicao->fornecedor->nome }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="fornecedor_email">Email Fornecedor:</label><br>
                <b>{{ $requisicao->fornecedor_email }}</b>
            </div>
            <div class="col-md-2 form-group borda_de_linha">
                <label for="data_previa_conclusao">Data Prévia Conclusão:</label><br>
                <b>{{ dataDbForm($requisicao->data_previa_conclusao) }}</b>
            </div>
            <div class="col-md-2 form-group borda_de_linha">
                <label for="">Simples Cotação:</label><br>
                <b>{{ $requisicao->simples_cotacao ? "Sim" : 'Não' }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2">
            <div class="col-md-4 form-group borda_de_linha">
                <label for="user_moderador_id">Solicitante:</label><br>
                <b>{{ $requisicao->criador->nome }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="user_moderador_id">Moderador:</label><br>
                <b>{{ $requisicao->moderador->nome }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="user_liberador_id">Liberador:</label><br>
                <b>{{ $requisicao->liberador->nome }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2 borda_de_linha">
            <div class="col-md-6 form-group borda_de_linha">
                <label for="setor_id">Setor:</label><br>
                <b>{{ $requisicao->setor->nome }}</b>
            </div>
            <div class="col-md-6 form-group borda_de_linha">
                <label for="unidade_id">Unidade:</label><br>
                <b>{{ $requisicao->unidade->nome }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2 borda_de_linha">
            <div class="col-md-12 form-group">
                <label for="motivo_pedido_compra">Motivo de Uso:</label><br>
                <b>{{ $requisicao->motivo_pedido_compra }}</b>
            </div>
        </div>
        <div class="row mt-2 gy-2 borda_de_linha">
            <div class="col-md-12 form-group">
                <label for="justificativa">Justificativa:</label><br>
                <b>{{ $requisicao->justificativa }}</b>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between mt-3 mb-3">
            <h5 class="card-title">Itens</h5>
        </div>
        <div class="table-responsive borda_de_linha">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th>Qtd</th>
                        <th>Unitário</th>
                        <th>Total</th>
                        <th>Entrega</th>
                        <th>Obs</th>
                        <th>Patrimonio</th>
                    </tr>
                </thead>
                <tbody id="tabela_items">
                    @foreach($requisicao->itens as $item)
                        <tr id="linha_item_cadastrada_{{ $item->id }}">
                            <td>{{ $item->item->nome }}</td>
                            <td>{{ $item->qtd_pedida }}</td>
                            <td>R${{ valorDbForm($item->valor_unid) }}</td>
                            <td>R${{ valorDbForm($item->valor_total_pedido) }}</td>
                            <td>{{ dataDbForm($item->data_previsao_entrega) }}</td>
                            <td>{{ $item->obs }}</td>
                            <td>{{ $item->lancar_patrimonio ? 'Sim' : 'Não' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <div class="row mt-2 gy-2 align-items-end">
            <div class="col-md-6 form-group borda_de_linha">
                <label for="qtd_itens_pedido">Total Quantidade:</label><br>
                <b>{{ $requisicao->qtd_itens_pedido }}</b>
            </div>
            <div class="col-md-6 form-group borda_de_linha">
                <label for="subtotal_pedido">Subtotal Pedido:</label><br>
                <b>R$ {{ valorDbForm($requisicao->subtotal_pedido) }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="frete">Frete:</label><br>
                <b>R$ {{ valorDbForm($requisicao->frete) }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="outras_despesas">Outras Despesas:</label><br>
                <b>R$ {{ valorDbForm($requisicao->outras_despesas) }}</b>
            </div>
            <div class="col-md-4 form-group borda_de_linha">
                <label for="desconto">Desconto:</label><br>
                <b>R$ {{ valorDbForm($requisicao->desconto) }}</b>
            </div>
            <div class="col-md-6 form-group borda_de_linha">
                <label for="acrescimo">Acrescimo:</label><br>
                <b>R$ {{ valorDbForm($requisicao->acrescimo) }}</b>
            </div>
            <div class="col-md-6 form-group borda_de_linha">
                <label for="total_pedido">Total Pedido:</label><br>
                <b>R$ {{ valorDbForm($requisicao->total_pedido) }}</b>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between mt-3 mb-3">
            <h5 class="card-title">Anexos</h5>
        </div>
        <div id="div_anexos">
            @if($requisicao->anexos()->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <th>Fornecedor</th>
                            <th>Arquivo</th>
                        </thead>
                        <tbody>
                            @foreach($requisicao->anexos as $anexo)
                                <tr id='linha_anexo_cadastrado_{{ $anexo->id }}'>
                                    <td>{{ $anexo->fornecedor->nome }}</td>
                                    <td>
                                        <a title="Abrir" target='_blank' href="/public/anexo_requisicoes/{{ $anexo->requisicao_id."/".$anexo->link_anexo }}" class="btn rounded-pill btn-icon btn-outline-primary waves-effect">
                                            <span class="tf-icons mdi mdi-folder-outline"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <hr>
        <div class="d-flex justify-content-between mt-3 mb-3">
            <h5 class="card-title">Financeiro</h5>
        </div>
        <div id="div_anexos">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <th>Id</th>
                        <th>Operação</th>
                        <th>Conta Pagamento</th>
                        <th>Cred/Deb</th>
                        <th>Tipo</th>
                        <th>Origem</th>
                        <th>Descrição</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Doc</th>
                        <th>Obs</th>
                    </thead>
                    <tbody>
                        @foreach($requisicao->financeiros()->orderBy('vencimento')->get() as $financeiro)
                            <tr>
                                <td>{{ $financeiro->id }}</td>
                                <td>{{ $financeiro->operacao->descricao }}</td>
                                <td>{{ $financeiro->conta_pagamento->descricao }}</td>
                                <td>{{ $financeiro->cred_deb }}</td>
                                <td>{{ $view_tipo_pagamento[$financeiro->tipo_pagamento] }}</td>
                                <td>{{ $financeiro->origem }}</td>
                                <td>{{ $financeiro->descricao }}</td>
                                <td>{{ dataDbForm($financeiro->vencimento) }}</td>
                                <td>R$ {{ valorDbForm($financeiro->valor) }}</td>
                                <td>{{ $financeiro->doc }}</td>
                                <td>{{ $financeiro->obs }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Histórico</h4>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body pt-4 pb-1">
                        <ul class="timeline card-timeline mb-0">
                            @foreach($requisicao->historicos()->orderByDesc('created_at')->get() as $historico)
                                @php
                                $var = explode(' ', $historico->created_at);
                                $dt_historico = dataDbForm($var[0])." ".$var[1];
                                @endphp
                                <li class="timeline-item timeline-item-transparent">
                                    <span class="timeline-point timeline-point-success"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header mb-1">
                                            <h6 class="mb-2">{{ $historico->user->nome." - Status:".$historico->status }}</h6>
                                            <small class="text-muted">{{ $dt_historico }}</small>
                                        </div>
                                        <p class="mb-2">{{ $historico->ds_historico }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('botao_cancelar_requisicao').addEventListener('click', ()=>{
    if(confirm('Tem certeza que deseja cancelar a requisição?')){
        mensagem = document.getElementById('mensagem').value;
        window.location.href = "{{ route('requisicoes.cancelar', $requisicao) }}?mensagem=" + mensagem;
    }
})
</script>
@endsection
