@extends('layout.admin')

@section('conteudo')
<style media="screen">
    .select2-selection__rendered{
        line-height: 40px !important;
        border-color: red !important;
    }
    .select2-selection{
        height: 40px !important;
    }

    .form-select, .input-group-text{
    height: 45px !important;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: none; /* Remove a seta */
  }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                @if($controle == "preparar_compra")
                    Preparar Compra - Cod: {{ $requisicao->id }}

                @elseif($retorno == 'compras')
                    Editar Compra - Cod: {{ $requisicao->id }}
                @else
                    Editar Requisição - Cod: {{ $requisicao->id }}
                @endif
            </h4>
        </div>
        <hr>
        <form id="formulario" action="{{ route('requisicoes.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
            <input type="hidden" name="contador_items" id="contador_items" value="0">
            <input type="hidden" name="contador_anexos" id="contador_anexos" value="1">
            <input type="hidden" name="contador_anexos_gerais" id="contador_anexos_gerais" value="0">
            <input type="hidden" name="contador_financeiro" id="contador_financeiro" value="0">
            <input type="hidden" name="controle" value="{{ $controle == 'preparar_compra' ? 'preparar_compra' : '' }}">
            <input type="hidden" name="controle_enviar_moderacao" id="controle_enviar_moderacao">
            <input type="hidden" name="retorno" value="{{ $retorno }}">
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select id="fornecedor_id" name="fornecedor_id" class="select2 form-select">
                            <option selected value="{{ $requisicao->fornecedor ? $requisicao->fornecedor->id : '' }}">{{ $requisicao->fornecedor ? $requisicao->fornecedor->nome : '' }}</option>
                        </select>
                        <label for="fornecedor_id">Fornecedor:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select required id="setor_id" name='setor_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($setores as $setor)
                                <option @if($requisicao->setor_id == $setor->id) selected @endif value="{{ $setor->id }}">{{ $setor->nome }}</option>
                            @endforeach
                        </select>
                        <label for="setor_id">Setor:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select required id="unidade_id" name='unidade_id' disabled class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($unidades as $unidade)
                                <option @if($requisicao->unidade_id == $unidade->id) selected @endif value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                            @endforeach
                        </select>
                        <label for="unidade_id">Unidade:</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="email" id="fornecedor_email" name="fornecedor_email" value="{{ $requisicao->fornecedor_email }}"/>
                        <label for="fornecedor_email">Informe o Email atual do Fornecedor:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="fornecedor_whatsapp" name="fornecedor_whatsapp" value="{{ $requisicao->fornecedor_whatsapp }}" maxlength="15" onkeypress="mascara( this, mtel )"/>
                        <label for="fornecedor_whatsapp">Informe o Whatsapp atual do Fornecedor:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="portador" name="portador" value="{{ $requisicao->portador }}"/>
                        <label for="portador">Portador:</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <select required name='user_moderador_id' id='user_moderador_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($users as $user)
                                @if($user->perfil->administrador || ($user->perfil->moderar && $user->setores()->where('setor_id', $requisicao->setor_id)->count() > 0))
                                    <option @if($requisicao->user_moderador_id == $user->id) selected @endif value="{{ $user->id }}">{{ $user->nome }}</option>
                                @endif
                            @endforeach
                        </select>
                        <label for="user_moderador_id">Moderador:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <select required name='user_liberador_id' id='user_liberador_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($users as $user)
                                @if($user->perfil->administrador || ($user->perfil->aprovar && $user->setores()->where('setor_id', $requisicao->setor_id)->count() > 0)))
                                    <option @if($requisicao->user_liberador_id == $user->id) selected @endif value="{{ $user->id }}">{{ $user->nome }}</option>
                                @endif
                            @endforeach
                        </select>
                        <label for="user_liberador_id">Liberador:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="date" id="data_previa_conclusao" name="data_previa_conclusao" value="{{ $requisicao->data_previa_conclusao }}"/>
                        <label for="data_previa_conclusao">Data Prévia Conclusão:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="switch switch-lg switch-success">
                        <input type="checkbox" name="simples_cotacao" value="Sim" class="switch-input" @if($requisicao->simples_cotacao) checked @endif>
                        <span class="switch-toggle-slider">
                            <span class="switch-on"></span>
                            <span class="switch-off"></span>
                        </span>
                        <span class="switch-label">Simples Cotação</span>
                    </label>
                    <label class="switch switch-lg switch-success">
                        <input type="checkbox" name="sem_validacao" id="sem_validacao" value="Sim" class="switch-input" @if($requisicao->sem_validacao) checked @endif>
                        <span class="switch-toggle-slider">
                            <span class="switch-on"></span>
                            <span class="switch-off"></span>
                        </span>
                        <span class="switch-label">Sem Validação</span>
                    </label>
                </div>
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline">
                        <textarea readonly class="form-control h-px-100" id="motivo_pedido_compra" name="motivo_pedido_compra">{{ $requisicao->motivo_pedido_compra }}</textarea>
                        <label for="motivo_pedido_compra">Item / Motivo:</label>
                      </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control h-px-100" id="justificativa" name="justificativa">{{ $requisicao->justificativa }}</textarea>
                        <label for="justificativa">Justificativa:</label>
                      </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control h-px-100" id="documentos" name="documentos">{{ $requisicao->documentos }}</textarea>
                        <label for="documentos">Documentos:</label>
                      </div>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between mt-3 mb-3">
                <h5 class="card-title">Itens</h5>
                <button class="btn btn-sm btn-primary" type="button" id="botao_adicionar_item">Adicionar Item</button>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Unidade</th>
                            <th>Qtd</th>
                            <th>Unitário</th>
                            <th>Total</th>
                            <th style='display:none'>Entrega</th>
                            <th>Obs</th>
                            <th>Patrimonio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tabela_items">
                        @foreach($requisicao->itens as $item)
                            <tr id="linha_item_cadastrada_{{ $item->id }}">
                                <td>{{ $item->item->nome }}</td>
                                <td>{{ $item->ds_unidade }}</td>
                                <td>
                                    <input onblur="calcula_total_item_cad({{ $item->id }})" class="form-control quantidade" type="number" name="item_cad_qtd_pedida_{{ $item->id }}" id="item_cad_qtd_pedida_{{ $item->id }}" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any" value="{{ $item->qtd_pedida }}"/>
                                </td>
                                <td>
                                    <input onblur="calcula_total_item_cad({{ $item->id }})" class="form-control" type="text" id="item_cad_valor_unid_{{ $item->id }}" name="item_cad_valor_unid_{{ $item->id }}" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($item->valor_unid) }}"/>
                                </td>
                                <td>
                                    <input onblur="calcula_total_item_cad({{ $item->id }})" class="form-control total" type="text" id="item_cad_valor_total_{{ $item->id }}" name="item_cad_valor_total_{{ $item->id }}" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($item->valor_total_pedido) }}"/>
                                </td>
                                <td style='display:none'>
                                    <input class="form-control" type="date" id="item_cad_data_previsao_entrega_{{ $item->id }}" name="item_cad_data_previsao_entrega_{{ $item->id }}" value="{{ $item->data_previsao_entrega }}"/>
                                </td>
                                <td>
                                    <input class="form-control" type="text" id="item_cad_obs_{{ $item->id }}" name="item_cad_obs_{{ $item->id }}" value="{{ $item->obs }}"/>
                                </td>
                                <td>
                                    <select id="item_cad_lancar_patrimonio_{{ $item->id }}" name="item_cad_lancar_patrimonio_{{ $item->id }}" class="form-control">
                                        <option @if(!$item->lancar_patrimonio) selected @endif value="Não">Não</option>
                                        <option @if($item->lancar_patrimonio) selected @endif value="Sim">Sim</option>
                                    </select>
                                </td>
                                <td>
                                    <button title="Excluir" onclick="excluir_item_cadastrado({{ $item->id }})" type="button" class="btn rounded-pill btn-icon btn-outline-danger waves-effect">
                                        <span class="tf-icons mdi mdi-delete"></span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="qtd_itens_pedido" name="qtd_itens_pedido" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any" value="{{ $requisicao->qtd_itens_pedido }}"/>
                        <label for="qtd_itens_pedido">Total Quantidade:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="subtotal_pedido" name="subtotal_pedido" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($requisicao->subtotal_pedido) }}"/>
                        <label for="subtotal_pedido">Subtotal Pedido:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="frete" name="frete" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($requisicao->frete) }}"/>
                        <label for="frete">Frete:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="outras_despesas" name="outras_despesas" onkeypress="return(MascaraMoeda(this,'.',',',event))"  value="{{ valorDbForm($requisicao->outras_despesas) }}"/>
                        <label for="outras_despesas">Outras Despesas:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="desconto" name="desconto" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($requisicao->desconto) }}"/>
                        <label for="desconto">Desconto:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="acrescimo" name="acrescimo" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($requisicao->acrescimo) }}"/>
                        <label for="acrescimo">Acrescimo:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="total_pedido" name="total_pedido" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($requisicao->total_pedido) }}"/>
                        <label for="total_pedido">Total Pedido:</label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between mt-3 mb-3">
                <h5 class="card-title">Anexos</h5>
                <button class="btn btn-sm btn-primary" type="button" id="botao_adicionar_anexo">Adicionar Anexo</button>
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
                                            <button onclick="excluir_anexo_cadastrado({{ $anexo->id }})" title="Excluir" type="button" class="btn rounded-pill btn-icon btn-outline-danger waves-effect">
                                                <span class="tf-icons mdi mdi-delete"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                <div class="row mt-2 gy-4 align-items-end" id="linha_anexo_1">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select id="anexo_fornecedor_1" name="anexo_fornecedor_1" class="select2 form-select fornecedor_anexos">

                            </select>
                            <label for="anexo_fornecedor_1">Fornecedor 1:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="file" id="anexo_arquivo_1" name="anexo_arquivo_1"/>
                            <label for="anexo_arquivo_1">Anexo 1:</label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between mt-3 mb-3">
                <h5 class="card-title">Anexos Gerais</h5>
                <button class="btn btn-sm btn-primary" type="button" id="botao_adicionar_anexo_geral">Adicionar Anexo Geral</button>
            </div>
            <div id="div_anexos_gerais">
                @if($requisicao->anexos_gerais()->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <th>Arquivo</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach($requisicao->anexos_gerais as $anexo)
                                    <tr id='linha_anexo_geral_cadastrado_{{ $anexo->id }}'>
                                        <td>
                                            <a title="Abrir" target='_blank' href="/public/anexo_requisicoes/{{ $anexo->requisicao_id."/".$anexo->link_anexo }}" class="btn rounded-pill btn-icon btn-outline-primary waves-effect">
                                                <span class="tf-icons mdi mdi-folder-outline"></span>
                                            </a>
                                        </td>
                                        <td>
                                            <button onclick="excluir_anexo_geral_cadastrado({{ $anexo->id }})" title="Excluir" type="button" class="btn rounded-pill btn-icon btn-outline-danger waves-effect">
                                                <span class="tf-icons mdi mdi-delete"></span>
                                            </button>
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
                <button class="btn btn-sm btn-primary" type="button" id="botao_adicionar_financeiro">Adicionar Financeiro</button>
            </div>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Operação</th>
                            <th>Conta</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Doc</th>
                            <th>Obs</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tabela_financeiro">
                        @foreach($requisicao->financeiros as $financeiro)
                            <tr id="linha_financeiro_cadastrado_{{ $financeiro->id }}">
                                <td>
                                    <select required id="financeiro_cad_operacao_id_{{ $financeiro->id }}" name="financeiro_cad_operacao_id_{{ $financeiro->id }}" class="select2 form-select">
                                        <option value="">Opções</option>
                                        @foreach($operacoes as $operacao)
                                            <option @if($financeiro->operacao_id == $operacao->id) selected @endif value="{{ $operacao->id }}">{{ $operacao->descricao }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select required id="financeiro_cad_conta_pagamento_id_{{ $financeiro->id }}" name="financeiro_cad_conta_pagamento_id_{{ $financeiro->id }}" class="select2 form-select">
                                        <option value="">Opções</option>
                                        @foreach($contas as $conta)
                                            <option @if($financeiro->conta_pagamento_id == $conta->id) selected @endif value="{{ $conta->id }}">{{ $conta->descricao }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select required id="financeiro_cad_tipo_pagamento_{{ $financeiro->id }}" name="financeiro_cad_tipo_pagamento_{{ $financeiro->id }}" class="select2 form-select">
                                        <option value="">Opções</option>
                                        <option @if($financeiro->tipo_pagamento == "Pagamento Antecipado") selected @endif value="Pagamento Antecipado">Antecipado</option>
                                        <option @if($financeiro->tipo_pagamento == "Pagamento Pós Entrega") selected @endif value="Pagamento Pós Entrega">Avista</option>
                                        <option @if($financeiro->tipo_pagamento == "Pagamento Data Vencimento") selected @endif value="Pagamento Data Vencimento">A Prazo</option>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control" type="text" id="financeiro_cad_descricao_{{ $financeiro->id }}" name="financeiro_cad_descricao_{{ $financeiro->id }}" value="{{ $financeiro->descricao }}"/>
                                </td>
                                <td>
                                    <input class="form-control" type="date" id="financeiro_cad_vencimento_{{ $financeiro->id }}" name="financeiro_cad_vencimento_{{ $financeiro->id }}" value="{{ $financeiro->vencimento }}"/>
                                </td>
                                <td>
                                    <input class="form-control financeiro" onblur="calcula_total_financeiro()" data-tipo='{{ $financeiro->cred_deb }}' type="text" id="financeiro_cad_valor_{{ $financeiro->id }}" name="financeiro_cad_valor_{{ $financeiro->id }}" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="{{ valorDbForm($financeiro->valor) }}"/>
                                </td>
                                <td>
                                    <input class="form-control" type="text" id="financeiro_cad_doc_{{ $financeiro->id }}" name="financeiro_cad_doc_{{ $financeiro->id }}" value="{{ $financeiro->doc }}"/>
                                </td>
                                <td>
                                    <input class="form-control" type="text" id="financeiro_cad_obs_{{ $financeiro->id }}" name="financeiro_cad_obs_{{ $financeiro->id }}" value="{{ $financeiro->obs }}"/>
                                </td>
                                <td>
                                    <button onclick="excluir_financeiro_cadastrado({{ $financeiro->id }})" title="Excluir" type="button" class="btn rounded-pill btn-icon btn-outline-danger waves-effect">
                                        <span class="tf-icons mdi mdi-delete"></span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_financeiro()" class="form-control" type="text" id="total_financeiro" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                        <label for="total_pedido">Total Financeiro:</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-12 form-group">
                    <button type="button" id="botao_adicionar_requisicao" class="btn btn-primary me-2">Salvar</button>
                </div>
            </div>
            @if($controle == "preparar_compra")
                <div class="row mt-2 gy-4 align-items-end">
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <textarea class="form-control h-px-100" id="mensagem" name="mensagem"></textarea>
                            <label for="mensagem">Mensagem à Moderação:</label>
                          </div>
                    </div>
                </div>
                <div class="row mt-2 gy-4 align-items-end">
                    <div class="col-md-12 form-group">
                        <button type="button" id="botao_adicionar_requisicao_enviar_moderacao" class="btn btn-primary me-2">Salvar e Enviar para Moderação</button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="modal fade" id="modal_item" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="backDropModalTitle">Adicionar Item Requisição</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-2 gy-4 align-items-end">
                    <div class="col-md-6">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <select required id="grupo_id" class="select2 form-select">
                                    <option value="">Opções</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}">{{ $grupo->descricao }}</option>
                                    @endforeach
                                </select>
                                <label for="grupo_id">Grupo:</label>
                            </div>
                            <span title="Adicionar Outro Grupo" onclick="adicionar_novo_grupo()" class="input-group-text cursor-pointer">
                                <i class="mdi mdi-plus-circle-outline"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <select required id="item_id" class="select2 form-select">
                                    <option value="">Opções</option>
                                </select>
                                <label for="item_id">Item/Produto:</label>
                            </div>
                            <span title="Adicionar Outro Item" onclick="adicionar_novo_item()" class="input-group-text cursor-pointer">
                                <i class="mdi mdi-plus-circle-outline"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select required id="ds_unidade" class="select2 form-select">
                                <option value="">Opções</option>
                                <option value="CÁPSULA">CÁPSULA</option>
                                <option value="CARTELA">CARTELA</option>
                                <option value="CENTO">CENTO</option>
                                <option value="CONJUNTO">CONJUNTO</option>
                                <option value="CENTÍMETRO">CENTÍMETRO</option>
                                <option value="CENTIMETRO QUADRADO">CENTIMETRO QUADRADO</option>
                                <option value="CAIXA">CAIXA</option>
                                <option value="DUZIA">DUZIA</option>
                                <option value="EMBALAGEM">EMBALAGEM</option>
                                <option value="FARDO">FARDO</option>
                                <option value="FOLHA">FOLHA</option>
                                <option value="FRASCO">FRASCO</option>
                                <option value="GALÃO">GALÃO</option>
                                <option value="GARRAFA">GARRAFA</option>
                                <option value="GRAMAS">GRAMAS</option>
                                <option value="JOGO">JOGO</option>
                                <option value="QUILOGRAMA">QUILOGRAMA</option>
                                <option value="KIT">KIT</option>
                                <option value="LATA">LATA</option>
                                <option value="LITRO">LITRO</option>
                                <option value="METRO">METRO</option>
                                <option value="METRO QUADRADO">METRO QUADRADO</option>
                                <option value="METRO CÚBICO">METRO CÚBICO</option>
                                <option value="MILHEIRO">MILHEIRO</option>
                                <option value="MILILITRO">MILILITRO</option>
                                <option value="MEGAWATT HORA">MEGAWATT HORA</option>
                                <option value="PACOTE">PACOTE</option>
                                <option value="PALETE">PALETE</option>
                                <option value="PARES">PARES</option>
                                <option value="PEÇA">PEÇA</option>
                                <option value="POTE">POTE</option>
                                <option value="QUILATE">QUILATE</option>
                                <option value="RESMA">RESMA</option>
                                <option value="ROLO">ROLO</option>
                                <option value="SACO">SACO</option>
                                <option value="SACOLA">SACOLA</option>
                                <option value="TAMBOR">TAMBOR</option>
                                <option value="TANQUE">TANQUE</option>
                                <option value="TONELADA">TONELADA</option>
                                <option value="TUBO">TUBO</option>
                                <option value="UNIDADE">UNIDADE</option>
                                <option value="VASILHAME">VASILHAME</option>
                                <option value="VIDRO">VIDRO</option>
                            </select>
                            <label for="ds_unidade">Unidade:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input onblur="calcula_total_item()" class="form-control" type="number" id="qtd_pedida" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any"/>
                            <label for="qtd_pedida">Qta Pedida:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <input onblur="calcula_total_item()" class="form-control" type="text" id="valor_unid" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                            <label for="valor_unid">Valor Unitário:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <input onblur="calcula_total_item()" class="form-control" type="text" id="valor_total" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                            <label for="valor_total">Valor Total:</label>
                        </div>
                    </div>
                    <div style="display:none" class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="date" id="data_previsao_entrega" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                            <label for="data_previsao_entrega">Data Previsão Entrega:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="switch switch-lg switch-success">
                            <input type="checkbox" id="lancar_patrimonio" value="Sim" class="switch-input">
                            <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                            </span>
                            <span class="switch-label">Lançar Patrimonio</span>
                        </label>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <textarea class="form-control h-px-100" id="obs"></textarea>
                            <label for="obs">Observação:</label>
                          </div>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <button class="btn btn-primary" id="botao_salvar_item" type="button">Adicionar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_financeiro" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="backDropModalTitle">Adicionar Financeiro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-2 gy-4 align-items-end">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select required id="financeiro_operacao_id" class="form-select combobox">
                                <option value="">Opções</option>
                                @foreach($operacoes as $operacao)
                                    <option value="{{ $operacao->id }}">{{ $operacao->descricao }}</option>
                                @endforeach
                            </select>
                            <label for="financeiro_operacao_id">Operação:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select required id="financeiro_conta_pagamento_id" class="select2 form-select">
                                <option value="">Opções</option>
                                @foreach($contas as $conta)
                                    <option value="{{ $conta->id }}">{{ $conta->descricao }}</option>
                                @endforeach
                            </select>
                            <label for="financeiro_conta_pagamento_id">Conta Pagamento:</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <input onblur="verifica_descricao_financeiro(this)" class="form-control" type="text" id="financeiro_descricao"/>
                            <label for="financeiro_descricao">Descrição:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <select required id="financeiro_tipo_pagamento" class="select2 form-select">
                                <option value="">Opções</option>
                                <option value="Pagamento Antecipado">Antecipado</option>
                                <option value="Pagamento Pós Entrega">Avista</option>
                                <option value="Pagamento Data Vencimento">A Prazo</option>
                            </select>
                            <label for="financeiro_tipo_pagamento">Tipo:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="financeiro_doc"/>
                            <label for="financeiro_doc">Doc:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="financeiro_obs"/>
                            <label for="financeiro_obs">Obs:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="date" id="financeiro_vencimento"/>
                            <label for="financeiro_vencimento">Vencimento:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="financeiro_valor" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                            <label for="financeiro_valor">Valor:</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <button class="btn btn-primary" id="botao_salvar_financeiro" type="button">Adicionar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
var modalItem;
var modalFinanceiro;

//window.addEventListener('load',()=>{
//    $('.combobox').combobox();
//});

document.getElementById('botao_adicionar_item').addEventListener('click', ()=>{
    modalItem = new bootstrap.Modal(document.getElementById('modal_item'));
    modalItem.show();
});

document.getElementById('botao_adicionar_financeiro').addEventListener('click', ()=>{
    total_pedido = document.getElementById('total_pedido').value;
    total_financeiro = document.getElementById('total_financeiro').value;

    total_pedido = total_pedido.replaceAll('.','');
    total_pedido = parseFloat(total_pedido.replace(',','.'));

    total_financeiro = total_financeiro.replaceAll('.','');
    total_financeiro = parseFloat(total_financeiro.replace(',','.'));

    valor = total_pedido - total_financeiro;
    valor = valor.toFixed(4);
    valor = valor.replace('.',',');

    document.getElementById('financeiro_valor').value = valor;

    modalFinanceiro = new bootstrap.Modal(document.getElementById('modal_financeiro'));
    modalFinanceiro.show();
});

function verifica_descricao_financeiro(e){
    if(e.value){
        $.getJSON(
            "{{ route('requisicoes.verifica_descricao_financeiro') }}",
            {
                descricao : e.value
            },
            function(json){
                if(json.controle == 'true'){
                    alert('AVISO: Esta descrição de financeiro já foi utilizado no sistema.');
                }
            }
        );
    }
}

document.getElementById('grupo_id').addEventListener('change', (e)=>{
    if(e.target.value){
        $.getJSON(
            '{{ route("itens.buscarItemsGrupo") }}',
            {
                grupo_id : e.target.value
            },
            function(json){
                document.getElementById('item_id').innerHTML = json.items;
            }
        );
    }
    else{
        document.getElementById('item_id').innerHTML = "<option value=''>Opções</option>";
    }
});

//função calcula_total_item
function calcula_total_item(){
    qtd = parseFloat(document.getElementById('qtd_pedida').value);
    valor = document.getElementById('valor_unid').value;
    valor = valor.replaceAll('.','');
    valor = parseFloat(valor.replace(',','.'));
    if(qtd > 0 && valor > 0){
        total = qtd * valor;
        total = total.toFixed(4);
        total = total.replace('.',',');
        document.getElementById('valor_total').value = total;
    }
    else{
        document.getElementById('valor_total').value = '0,00';
    }
}

function calcula_total_item_cad(item_id){
    qtd = document.getElementById('item_cad_qtd_pedida_' + item_id).value;
    qtd = parseFloat(qtd.replace(',','.'));
    valor = document.getElementById('item_cad_valor_unid_' + item_id).value;
    valor = valor.replaceAll('.','');
    valor = parseFloat(valor.replace(',','.'));
    if(qtd > 0 && valor > 0){
        total = qtd * valor;
        total = total.toFixed(4);
        total = total.replace('.',',');
        document.getElementById('item_cad_valor_total_' + item_id).value = total;
    }
    else{
        document.getElementById('item_cad_valor_total_' + item_id).value = '0,0000';
    }
    calcula_total_somatorio();
}

document.getElementById('botao_salvar_item').addEventListener('click', ()=>{
    item_id = document.getElementById('item_id').value;
    nm_item = $('#item_id').find(":selected").text();
    qtd = document.getElementById('qtd_pedida').value;
    unitario = document.getElementById('valor_unid').value;
    total = document.getElementById('valor_total').value;
    data_previsao_entrega = document.getElementById('data_previsao_entrega').value;
    lancar_patrimonio = document.getElementById('lancar_patrimonio').checked == true ? 'Sim' : 'Não';
    obs = document.getElementById('obs').value;
    ds_unidade = document.getElementById('ds_unidade').value;

    if(item_id && qtd && unitario && total){
        contador = parseInt(document.getElementById('contador_items').value);
        contador++;
        document.getElementById('contador_items').value = contador;

        tr = document.createElement('tr');
        td1 = document.createElement('td');
        td2 = document.createElement('td');
        td3 = document.createElement('td');
        td4 = document.createElement('td');
        td5 = document.createElement('td');
        td5.setAttribute('style', 'display:none');
        td6 = document.createElement('td');
        td7 = document.createElement('td');
        td8 = document.createElement('td');
        td_unidade = document.createElement('td');
        button = document.createElement('button');
        input1 = document.createElement('input');
        input2 = document.createElement('input');
        input3 = document.createElement('input');
        input4 = document.createElement('input');
        input5 = document.createElement('input');
        input6 = document.createElement('input');
        input7 = document.createElement('input');
        input_unidade = document.createElement('input');

        variavel = data_previsao_entrega.split('-');
        dt_entrega = variavel[2] + '/' + variavel[1] + '/' + variavel[0];

        tr.setAttribute('id', 'linha_item_' + contador);

        td1.innerHTML = nm_item;
        td2.innerHTML = qtd;
        td3.innerHTML = 'R$ ' + unitario;
        td4.innerHTML = 'R$' + total;
        td5.innerHTML = dt_entrega;
        td6.innerHTML = obs;
        td7.innerHTML = lancar_patrimonio;
        td_unidade.innerHTML = ds_unidade;

        button.setAttribute('type', 'button');
        button.setAttribute('onclick', 'excluir_item(' + contador + ')');
        button.setAttribute('class', 'btn rounded-pill btn-icon btn-outline-danger waves-effect');
        button.innerHTML = '<span class="tf-icons mdi mdi-delete"></span>';

        td8.appendChild(button);

        tr.appendChild(td1);
        tr.appendChild(td_unidade);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tr.appendChild(td4);
        tr.appendChild(td5);
        tr.appendChild(td6);
        tr.appendChild(td7);
        tr.appendChild(td8);

        input1.setAttribute('type','hidden');
        input1.setAttribute('name','item_id_' + contador);
        input1.setAttribute('value', item_id);

        input2.setAttribute('type','hidden');
        input2.setAttribute('name','qtd_pedida_' + contador);
        input2.setAttribute('class','quantidade');
        input2.setAttribute('value', qtd);

        input3.setAttribute('type','hidden');
        input3.setAttribute('name','valor_unid_' + contador);
        input3.setAttribute('class','unitario');
        input3.setAttribute('value', unitario);

        input4.setAttribute('type','hidden');
        input4.setAttribute('name','valor_total_' + contador);
        input4.setAttribute('class','total');
        input4.setAttribute('value', total);

        input5.setAttribute('type','hidden');
        input5.setAttribute('name','data_previsao_entrega_' + contador);
        input5.setAttribute('value', data_previsao_entrega);

        input6.setAttribute('type','hidden');
        input6.setAttribute('name','obs_' + contador);
        input6.setAttribute('value', obs);

        input7.setAttribute('type','hidden');
        input7.setAttribute('name','lancar_patrimonio' + contador);
        input7.setAttribute('value', lancar_patrimonio);

        input_unidade.setAttribute('type','hidden');
        input_unidade.setAttribute('name','ds_unidade_' + contador);
        input_unidade.setAttribute('value', ds_unidade);

        tr.appendChild(input1);
        tr.appendChild(input2);
        tr.appendChild(input3);
        tr.appendChild(input4);
        tr.appendChild(input5);
        tr.appendChild(input6);
        tr.appendChild(input7);
        tr.appendChild(input_unidade);

        document.getElementById('tabela_items').appendChild(tr);
        modalItem.hide();
        calcula_total_somatorio();
    }
    else{
        alert('Preencha todos os campos');
    }
})

function calcula_total_somatorio(){
    //quantidade
    let somatorio = 0;
    inputs = document.querySelectorAll('input.quantidade');
    [].forEach.call(inputs, function(input) {
        variavel = input.value;
        variavel = parseFloat(variavel);
        if(variavel > 0){
            somatorio = somatorio + variavel;
        }
    });
    somatorio = somatorio.toFixed(4);
    document.getElementById('qtd_itens_pedido').value = somatorio;

    //total
    somatorio = 0;
    inputs = document.querySelectorAll('input.total');
    [].forEach.call(inputs, function(input) {
        variavel = input.value;
        variavel = variavel.replaceAll('.','');
        variavel = variavel.replace(',','.');
        variavel = parseFloat(variavel);
        if(variavel > 0){
            somatorio = somatorio + variavel;
        }
    });
    total_itens = somatorio;
    somatorio = somatorio.toFixed(4);
    somatorio = somatorio.replace('.',",");
    document.getElementById('subtotal_pedido').value = somatorio;

    frete = document.getElementById('frete').value;
    frete = frete.replaceAll('.','');
    frete = frete.replace(',','.');
    frete = parseFloat(frete);

    outras_despesas = document.getElementById('outras_despesas').value;
    outras_despesas = outras_despesas.replaceAll('.','');
    outras_despesas = outras_despesas.replace(',','.');
    outras_despesas = parseFloat(outras_despesas);

    desconto = document.getElementById('desconto').value;
    desconto = desconto.replaceAll('.','');
    desconto = desconto.replace(',','.');
    desconto = parseFloat(desconto);

    acrescimo = document.getElementById('acrescimo').value;
    acrescimo = acrescimo.replaceAll('.','');
    acrescimo = acrescimo.replace(',','.');
    acrescimo = parseFloat(acrescimo);

    total_despesas = total_itens + frete + outras_despesas + acrescimo - desconto;
    total_despesas = total_despesas.toFixed(4);
    total_despesas = total_despesas.replace('.',",");
    document.getElementById('total_pedido').value = total_despesas;
}

function excluir_item(linha){
    if(confirm('Tem certeza que deseja excluir o item?')){
        document.getElementById('linha_item_' + linha).remove();
        calcula_total_somatorio();
    }
}

document.getElementById('botao_adicionar_anexo').addEventListener('click', ()=>{
    contador = parseInt(document.getElementById('contador_anexos').value);
    contador++;
    document.getElementById('contador_anexos').value = contador;
    row = document.createElement('div');
    row.setAttribute('class', 'row mt-2 gy-4 align-items-end');
    row.setAttribute('id', 'linha_anexo_' + contador);

    row.innerHTML = `
    <div class='col-md-6'>
        <div class="form-floating form-floating-outline">
            <select id="anexo_fornecedor_${contador}" name="anexo_fornecedor_${contador}" class="select2 form-select fornecedor_anexos">

            </select>
            <label for="anexo_fornecedor_${contador}">Fornecedor:</label>
        </div>
    </div>
    <div class='col-md-6'>
        <div class='form-floating form-floating-outline'>
            <input class='form-control' type='file' id='anexo_arquivo_${contador}' name='anexo_arquivo_${contador}'/>
            <label for='anexo_arquivo_${contador}'>Anexo ${contador}:</label>
        </div>
    </div>
    `;

    document.getElementById('div_anexos').appendChild(row);

    $('#anexo_fornecedor_' + contador).select2({
        placeholder: "Escolha o Fornecedor.",
        allowClear: true,
        minimumInputLength: 2,
        ajax:{
            url:"{{ route('fornecedores.get_fornecedor_select') }}",
            dataType: "json",
            type: 'GET',
            delay: 250,
            data:function(params){
                return {
                    q: params.term,
                };
            },
            processResults: function(data){
                return {
                    results:data
                };
            },
        cache: true
        }
    });

})

document.getElementById('botao_adicionar_requisicao').addEventListener('click', ()=>{
    if(document.getElementById('fornecedor_id').value == ''){
        alert('É necessário preencher o Fornecedor');
        document.getElementById('fornecedor_id').focus();
        return;
    }

    if(document.getElementById('setor_id').value == ""){
        alert('É necessário preencher o Setor');
        document.getElementById('setor_id').focus();
        return;
    }

    if(document.getElementById('unidade_id').value == ""){
        alert('É necessário preencher o Unidade');
        document.getElementById('unidade_id').focus();
        return;
    }

    if(document.getElementById('user_moderador_id').value == ""){
        alert('É necessário preencher o Moderador');
        document.getElementById('user_moderador_id').focus();
        return;
    }

    if(document.getElementById('user_liberador_id').value == ""){
        alert('É necessário preencher o Liberador');
        document.getElementById('user_liberador_id').focus();
        return;
    }

    if(document.getElementById('data_previa_conclusao').value == ""){
        alert('É necessário preencher a Data Prévia de Conclusão');
        document.getElementById('data_previa_conclusao').focus();
        return;
    }

    //if(document.getElementById('fornecedor_email').value == "" && document.getElementById('fornecedor_whatsapp').value == ""){
    //    alert('É necessário preencher o email ou whatsapp atual do fornecedor');
    //    document.getElementById('data_previa_conclusao').focus();
    //    return;
    //}

    //vamos verificar se o financeiro esta correto ou não
    total_financeiro = document.getElementById('total_financeiro').value;
    total_financeiro = total_financeiro.replaceAll('.','');
    total_financeiro = parseFloat(total_financeiro.replace(',','.'));
    total_financeiro.toFixed(2);

    total_pedido = document.getElementById('total_pedido').value;
    total_pedido = total_pedido.replaceAll('.','');
    total_pedido = parseFloat(total_pedido.replace(',','.'));
    total_pedido.toFixed(2);

    if(total_financeiro == total_pedido){
        document.getElementById('formulario').submit();
    }
    else{
        if(confirm('Total do Pedido e Total Financeiro não conferem, continuar com o cadastro mesmo assim.')){
            document.getElementById('formulario').submit();
        }
    }

});

@if($controle == "preparar_compra")
    document.getElementById('botao_adicionar_requisicao_enviar_moderacao').addEventListener('click', ()=>{
    if(document.getElementById('fornecedor_id').value == ''){
        alert('É necessário preencher o Fornecedor');
        document.getElementById('fornecedor_id').focus();
        return;
    }

    if(document.getElementById('setor_id').value == ""){
        alert('É necessário preencher o Setor');
        document.getElementById('setor_id').focus();
        return;
    }

    if(document.getElementById('unidade_id').value == ""){
        alert('É necessário preencher o Unidade');
        document.getElementById('unidade_id').focus();
        return;
    }

    if(document.getElementById('user_moderador_id').value == ""){
        alert('É necessário preencher o Moderador');
        document.getElementById('user_moderador_id').focus();
        return;
    }

    if(document.getElementById('user_liberador_id').value == ""){
        alert('É necessário preencher o Liberador');
        document.getElementById('user_liberador_id').focus();
        return;
    }

    if(document.getElementById('data_previa_conclusao').value == ""){
        alert('É necessário preencher a Data Prévia de Conclusão');
        document.getElementById('data_previa_conclusao').focus();
        return;
    }

    //if(document.getElementById('fornecedor_email').value == ""){
    //    alert('É necessário preencher o email atual do fornecedor');
    //    document.getElementById('data_previa_conclusao').focus();
    //    return;
    //}

    //vamos verificar se o financeiro esta correto ou não
    total_financeiro = document.getElementById('total_financeiro').value;
    total_financeiro = total_financeiro.replaceAll('.','');
    total_financeiro = parseFloat(total_financeiro.replace(',','.'));
    total_financeiro.toFixed(4);

    total_pedido = document.getElementById('total_pedido').value;
    total_pedido = total_pedido.replaceAll('.','');
    total_pedido = parseFloat(total_pedido.replace(',','.'));
    total_pedido.toFixed(4);

    if(total_financeiro == total_pedido){
        document.getElementById('controle_enviar_moderacao').value = 'sim';
        document.getElementById('formulario').submit();
    }
    else{
        alert('Total do Pedido e Total Financeiro não conferem, por este motivo esta requisição não pode avançar para a moderação.');
    }

});
@endif

function excluir_item_cadastrado(item_id){
    if(confirm('Tem certeza que deseja excluir o item? Esta ação não poderá ser revertida.')){
        $.getJSON(
            '{{ route("requisicoes.itens.delete") }}',
            {
                item_id : item_id
            },
            function(json){
                if(json.controle == 'true'){
                    document.getElementById('linha_item_cadastrada_' + json.item_id).remove();
                    calcula_total_somatorio();
                }
            }
        );
    }
}

function excluir_anexo_cadastrado(anexo_id){
    if(confirm('Tem certeza que deseja excluir o anexo? Esta ação não poderá ser revertida.')){
        $.getJSON(
            '{{ route("requisicoes.anexo.delete") }}',
            {
                anexo_id : anexo_id
            },
            function(json){
                if(json.controle == 'true'){
                    document.getElementById('linha_anexo_cadastrado_' + json.anexo_id).remove();
                }
            }
        );
    }
}

document.getElementById('botao_salvar_financeiro').addEventListener('click', ()=>{
    operacao_id = document.getElementById('financeiro_operacao_id').value;
    conta_pagamento_id = document.getElementById('financeiro_conta_pagamento_id').value;
    tipo_pagamento = document.getElementById('financeiro_tipo_pagamento').value;
    descricao = document.getElementById('financeiro_descricao').value;
    vencimento = document.getElementById('financeiro_vencimento').value;
    valor = document.getElementById('financeiro_valor').value;

    if(operacao_id && conta_pagamento_id && tipo_pagamento && descricao && valor){
        if(tipo_pagamento != "Pagamento Pós Entrega"){
            if(!vencimento){
                alert('É necessário preencher os campos, Operação, Conta Pagamento, Crédito ou Débito, Tipo Pagamento, Origem, Descrição, Vencimento e Valor');
                return;
            }
        }

        if(tipo_pagamento == 'Pagamento Pós Entrega'){
            view_tipo_pagamento = 'Avista';
        }
        else if(tipo_pagamento == 'Pagamento Antecipado'){
            view_tipo_pagamento = 'Antecipado';
        }
        else if(tipo_pagamento == 'Pagamento Data Vencimento'){
            view_tipo_pagamento = 'A Prazo';
        }

        contador = parseInt(document.getElementById('contador_financeiro').value);
        contador++;
        document.getElementById('contador_financeiro').value = contador;

        nm_operacao = $('#financeiro_operacao_id').find(":selected").text();
        nm_conta = $('#financeiro_conta_pagamento_id').find(":selected").text();

        doc = document.getElementById('financeiro_doc').value;
        obs = document.getElementById('financeiro_obs').value;

        data_vencimento = vencimento.split('-');

        tr = document.createElement('tr');
        td1 = document.createElement('td');
        td2 = document.createElement('td');
        td4 = document.createElement('td');
        td6 = document.createElement('td');
        td7 = document.createElement('td');
        td8 = document.createElement('td');
        td9 = document.createElement('td');
        td10 = document.createElement('td');
        td11 = document.createElement('td');
        botao = document.createElement('button');
        input1 = document.createElement('input');
        input2 = document.createElement('input');
        input4 = document.createElement('input');
        input6 = document.createElement('input');
        input7 = document.createElement('input');
        input8 = document.createElement('input');
        input9 = document.createElement('input');
        input10 = document.createElement('input');

        tr.setAttribute('id', 'linha_financeiro_' + contador);

        td1.innerHTML = nm_operacao;
        td2.innerHTML = nm_conta;
        td4.innerHTML = view_tipo_pagamento;
        td6.innerHTML = descricao;
        if(vencimento){
            td7.innerHTML = data_vencimento[2] + '/' + data_vencimento[1] + '/' + data_vencimento[0];
        }
        else{
            td7.innerHTML = '';
        }
        td8.innerHTML = valor;
        td9.innerHTML = doc;
        td10.innerHTML = obs;

        botao.setAttribute('type', 'button');
        botao.setAttribute('onclick', 'excluir_financeiro(' + contador + ')');
        botao.setAttribute('class', 'btn rounded-pill btn-icon btn-outline-danger waves-effect');
        botao.innerHTML = '<span class="tf-icons mdi mdi-delete"></span>';

        td11.appendChild(botao)

        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td4);
        tr.appendChild(td6);
        tr.appendChild(td7);
        tr.appendChild(td8);
        tr.appendChild(td9);
        tr.appendChild(td10);
        tr.appendChild(td11);

        input1.setAttribute('type', 'hidden');
        input1.setAttribute('name', 'operacao_id_' + contador);
        input1.setAttribute('value', operacao_id);

        input2.setAttribute('type', 'hidden');
        input2.setAttribute('name', 'conta_pagamento_id_' + contador);
        input2.setAttribute('value', conta_pagamento_id);

        input4.setAttribute('type', 'hidden');
        input4.setAttribute('name', 'tipo_pagamento_' + contador);
        input4.setAttribute('value', tipo_pagamento);

        input6.setAttribute('type', 'hidden');
        input6.setAttribute('name', 'descricao_' + contador);
        input6.setAttribute('value', descricao);

        input7.setAttribute('type', 'hidden');
        input7.setAttribute('name', 'vencimento_' + contador);
        input7.setAttribute('value', vencimento);

        input8.setAttribute('type', 'hidden');
        input8.setAttribute('class', 'financeiro');
        input8.setAttribute('name', 'valor_' + contador);
        input8.setAttribute('value', valor);

        input9.setAttribute('type', 'hidden');
        input9.setAttribute('name', 'doc_' + contador);
        input9.setAttribute('value', doc);

        input10.setAttribute('type', 'hidden');
        input10.setAttribute('name', 'obs_' + contador);
        input10.setAttribute('value', obs);

        tr.appendChild(input1);
        tr.appendChild(input2);
        tr.appendChild(input4);
        tr.appendChild(input6);
        tr.appendChild(input7);
        tr.appendChild(input8);
        tr.appendChild(input9);
        tr.appendChild(input10);

        document.getElementById('tabela_financeiro').appendChild(tr);
        modalFinanceiro.hide();
        calcula_total_financeiro();
        document.getElementById('financeiro_operacao_id').value = '';
        document.getElementById('financeiro_conta_pagamento_id').value = '';
        document.getElementById('financeiro_tipo_pagamento').value = '';
        document.getElementById('financeiro_descricao').value = '';
        document.getElementById('financeiro_vencimento').value = '';
        document.getElementById('financeiro_valor').value = '';
        document.getElementById('financeiro_obs').value = '';
        document.getElementById('financeiro_doc').value = '';
    }
    else{
        alert('É necessário preencher os campos, Operação, Conta Pagamento, Crédito ou Débito, Tipo Pagamento, Origem, Descrição, Vencimento e Valor');
    }
})

function excluir_financeiro(linha){
    if(confirm('Tem certeza que deseja excluir o financeiro selecionado?')){
        document.getElementById('linha_financeiro_' + linha).remove();
        calcula_total_financeiro();
    }
}

function excluir_financeiro_cadastrado(financeiro_id){
    if(confirm('Tem certeza que deseja excluir este financeiro? Esta ação não poderá ser desfeita.')){
        $.getJSON(
            '{{ route("requisicoes.financeiro.delete") }}',
            {
                financeiro_id : financeiro_id
            },
            function(json){
                if(json.controle == "true"){
                    document.getElementById('linha_financeiro_cadastrado_' + json.financeiro_id).remove();
                    calcula_total_financeiro();
                }
            }
        );
    }
}

function calcula_total_financeiro(){
    somatorio = 0;
    inputs = document.querySelectorAll('input.financeiro');
    [].forEach.call(inputs, function(input) {
        variavel = input.value;
        variavel = variavel.replaceAll('.','');
        variavel = variavel.replace(',','.');
        variavel = parseFloat(variavel);
        if(variavel > 0){
            somatorio = somatorio + variavel;
        }
    });
    somatorio = somatorio.toFixed(4);
    somatorio = somatorio.replace('.',",");
    document.getElementById('total_financeiro').value = somatorio;
}

function muda_cred_deb_financeiro(e, financeiro_id){
    document.getElementById('financeiro_cad_valor_' + financeiro_id).setAttribute('data-tipo', e.value);
    calcula_total_financeiro();
}

window.addEventListener('load', ()=>{
    calcula_total_financeiro();
    $('.combobox').combobox();

    $('#fornecedor_id').select2({
        placeholder: "Escolha o Fornecedor.",
        allowClear: true,
        minimumInputLength: 2,
        ajax:{
            url:"{{ route('fornecedores.get_fornecedor_select') }}",
            dataType: "json",
            type: 'GET',
            delay: 250,
            data:function(params){
                return {
                    q: params.term,
                };
            },
            processResults: function(data){
                return {
                    results:data
                };
            },
        cache: true
        }
    });

    $('.fornecedor_anexos').select2({
        placeholder: "Escolha o Fornecedor.",
        allowClear: true,
        minimumInputLength: 2,
        ajax:{
            url:"{{ route('fornecedores.get_fornecedor_select') }}",
            dataType: "json",
            type: 'GET',
            delay: 250,
            data:function(params){
                return {
                    q: params.term,
                };
            },
            processResults: function(data){
                return {
                    results:data
                };
            },
        cache: true
        }
    });

})

function adicionar_novo_item(){
    if(document.getElementById('grupo_id').value != ""){
        nm_item = prompt('Informe o nome do novo item.');
        if(nm_item){
            $.getJSON(
                '{{ route("pedidos.itens.insert") }}',
                {
                    nm_item : nm_item,
                    grupo_id : document.getElementById('grupo_id').value
                },
                function(json){
                    document.getElementById('item_id').innerHTML = json.html;
                }
            );
        }
    }
    else{
        alert('É necessário escolher o grupo do item.');
    }
}

function adicionar_novo_grupo(){

    nm_grupo = prompt('Informe o nome do novo grupo.');
    if(nm_grupo){
        $.getJSON(
            '{{ route("pedidos.grupos.insert") }}',
            {
                nm_grupo : nm_grupo,
            },
            function(json){
                document.getElementById('grupo_id').innerHTML = json.html;
            }
        );
    }

}

document.getElementById('botao_adicionar_anexo_geral').addEventListener('click', ()=>{
    contador = parseInt(document.getElementById('contador_anexos_gerais').value);
    contador++;
    document.getElementById('contador_anexos_gerais').value = contador;
    row = document.createElement('div');
    row.setAttribute('class', 'row mt-2 gy-4 align-items-end');
    row.setAttribute('id', 'linha_anexo_geral_' + contador);

    row.innerHTML = `
    <div class='col-md-12'>
        <div class='form-floating form-floating-outline'>
            <input class='form-control' type='file' id='anexo_geral_arquivo_${contador}' name='anexo_geral_arquivo_${contador}'/>
            <label for='anexo_geral_arquivo_${contador}'>Anexo Geral ${contador}:</label>
        </div>
    </div>
    `;

    document.getElementById('div_anexos_gerais').appendChild(row);
})

function excluir_anexo_geral_cadastrado(anexo_id){
    if(confirm('Tem certeza que deseja excluir este anexo? Esta ação não poderá ser desfeita.')){
        $.getJSON(
            '{{ route("requisicoes.anexo_geral.delete") }}',
            {
                anexo_id : anexo_id
            },
            function(json){
                if(json.controle == "true"){
                    document.getElementById('linha_anexo_geral_cadastrado_' + json.anexo_id).remove();
                }
            }
        );
    }
}
</script>
@endsection
