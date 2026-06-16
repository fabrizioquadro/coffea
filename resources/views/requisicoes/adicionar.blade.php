@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Adicionar Requisição</h4>
        </div>
        <hr>
        <form id="formulario" action="{{ route('requisicoes.insert') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="contador_items" id="contador_items" value="0">
            <input type="hidden" name="contador_anexos" id="contador_anexos" value="1">
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select required id="fornecedor_id" name='fornecedor_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($fornecedores as $fornecedor)
                                <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome }}</option>
                            @endforeach
                        </select>
                        <label for="fornecedor_id">Fornecedor:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select required id="setor_id" name='setor_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($setores as $setor)
                                <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                            @endforeach
                        </select>
                        <label for="setor_id">Setor:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select required id="unidade_id" name='unidade_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($unidades as $unidade)
                                <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                            @endforeach
                        </select>
                        <label for="unidade_id">Unidade:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <select required name='user_moderador_id' id='user_moderador_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($users as $user)
                                @if($user->perfil->administrador || $user->perfil->moderar)
                                    <option value="{{ $user->id }}">{{ $user->nome }}</option>
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
                                @if($user->perfil->administrador || $user->perfil->aprovar)
                                    <option value="{{ $user->id }}">{{ $user->nome }}</option>
                                @endif
                            @endforeach
                        </select>
                        <label for="user_liberador_id">Liberador:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="date" id="data_previa_conclusao" name="data_previa_conclusao"/>
                        <label for="data_previa_conclusao">Data Prévia Conclusão:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="switch switch-lg switch-success">
                        <input type="checkbox" name="simples_cotacao" value="Sim" class="switch-input">
                        <span class="switch-toggle-slider">
                            <span class="switch-on"></span>
                            <span class="switch-off"></span>
                        </span>
                        <span class="switch-label">Simples Cotação</span>
                    </label>
                </div>
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control h-px-100" id="motivo_pedido_compra" name="motivo_pedido_compra"></textarea>
                        <label for="motivo_pedido_compra">Motivo Pedido Compra:</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control h-px-100" id="justificativa" name="justificativa"></textarea>
                        <label for="justificativa">Justificativa:</label>
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
                            <th>Qtd</th>
                            <th>Unitário</th>
                            <th>Total</th>
                            <th>Entrega</th>
                            <th>Obs</th>
                            <th>Patrimonio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tabela_items">

                    </tbody>
                </table>
            </div>
            <hr>
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="qtd_itens_pedido" name="qtd_itens_pedido" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any"/>
                        <label for="qtd_itens_pedido">Total Quantidade:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="subtotal_pedido" name="subtotal_pedido" onkeypress="return(MascaraMoeda(this,'.',',',event))"/>
                        <label for="subtotal_pedido">Subtotal Pedido:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="frete" name="frete" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="0,00"/>
                        <label for="frete">Frete:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="outras_despesas" name="outras_despesas" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="0,00"/>
                        <label for="outras_despesas">Outras Despesas:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="desconto" name="desconto" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="0,00"/>
                        <label for="desconto">Desconto:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="acrescimo" name="acrescimo" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="0,00"/>
                        <label for="acrescimo">Acrescimo:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="total_pedido" name="total_pedido" onkeypress="return(MascaraMoeda(this,'.',',',event))" value="0,00"/>
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
                <div class="row mt-2 gy-4 align-items-end" id="linha_anexo_1">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select required id="type" name='anexo_fornecedor_1' class="select2 form-select">
                                <option value="">Opções</option>
                                @foreach($fornecedores as $fornecedor)
                                    <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome }}</option>
                                @endforeach
                            </select>
                            <label for="axexo_fornecedor_1">Fornecedor 1:</label>
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
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-12 form-group">
                    <button type="button" id="botao_adicionar_requisicao" class="btn btn-primary me-2">Salvar</button>
                </div>
            </div>
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
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <select required id="grupo_id" class="select2 form-select">
                                <option value="">Opções</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}">{{ $grupo->descricao }}</option>
                                @endforeach
                            </select>
                            <label for="grupo_id">Grupo:</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-floating form-floating-outline">
                            <select required id="item_id" class="select2 form-select">
                                <option value="">Opções</option>
                            </select>
                            <label for="item_id">Item/Produto:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="date" id="data_previsao_entrega"/>
                            <label for="data_previsao_entrega">Data Previsão Entrega:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
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

<script>
var modalItem;

document.getElementById('botao_adicionar_item').addEventListener('click', ()=>{
    $('#modal_item form')[0].reset();
    $('#grupo_id').val('').trigger('change');
    $('#item_id').val('').trigger('change');
    $('#ds_unidade').val('').trigger('change');

    modalItem = new bootstrap.Modal(document.getElementById('modal_item'));
    modalItem.show();
});

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
    valor = valor.replace('.','');
    valor = parseFloat(valor.replace(',','.'));
    if(qtd > 0 && valor > 0){
        total = qtd * valor;
        total = total.toFixed(2);
        total = total.replace('.',',');
        document.getElementById('valor_total').value = total;
    }
    else{
        document.getElementById('valor_total').value = '0,00';
    }

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

    if(item_id && qtd && unitario && total && data_previsao_entrega){
        contador = parseInt(document.getElementById('contador_items').value);
        contador++;
        document.getElementById('contador_items').value = contador;

        tr = document.createElement('tr');
        td1 = document.createElement('td');
        td2 = document.createElement('td');
        td3 = document.createElement('td');
        td4 = document.createElement('td');
        td5 = document.createElement('td');
        td6 = document.createElement('td');
        td7 = document.createElement('td');
        td8 = document.createElement('td');
        button = document.createElement('button');
        input1 = document.createElement('input');
        input2 = document.createElement('input');
        input3 = document.createElement('input');
        input4 = document.createElement('input');
        input5 = document.createElement('input');
        input6 = document.createElement('input');
        input7 = document.createElement('input');

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

        button.setAttribute('type', 'button');
        button.setAttribute('onclick', 'excluir_item(' + contador + ')');
        button.setAttribute('class', 'btn rounded-pill btn-icon btn-outline-danger waves-effect');
        button.innerHTML = '<span class="tf-icons mdi mdi-delete"></span>';

        td8.appendChild(button);

        tr.appendChild(td1);
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

        tr.appendChild(input1);
        tr.appendChild(input2);
        tr.appendChild(input3);
        tr.appendChild(input4);
        tr.appendChild(input5);
        tr.appendChild(input6);
        tr.appendChild(input7);

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
    document.getElementById('qtd_itens_pedido').value = somatorio;

    //total
    somatorio = 0;
    inputs = document.querySelectorAll('input.total');
    [].forEach.call(inputs, function(input) {
        variavel = input.value;
        variavel = variavel.replace('.','');
        variavel = variavel.replace(',','.');
        variavel = parseFloat(variavel);
        if(variavel > 0){
            somatorio = somatorio + variavel;
        }
    });
    total_itens = somatorio;
    somatorio = somatorio.toFixed(2);
    somatorio = somatorio.replace('.',",");
    document.getElementById('subtotal_pedido').value = somatorio;

    frete = document.getElementById('frete').value;
    frete = frete.replace('.','');
    frete = frete.replace(',','.');
    frete = parseFloat(frete);

    outras_despesas = document.getElementById('outras_despesas').value;
    outras_despesas = outras_despesas.replace('.','');
    outras_despesas = outras_despesas.replace(',','.');
    outras_despesas = parseFloat(outras_despesas);

    desconto = document.getElementById('desconto').value;
    desconto = desconto.replace('.','');
    desconto = desconto.replace(',','.');
    desconto = parseFloat(desconto);

    acrescimo = document.getElementById('acrescimo').value;
    acrescimo = acrescimo.replace('.','');
    acrescimo = acrescimo.replace(',','.');
    acrescimo = parseFloat(acrescimo);

    total_despesas = total_itens + frete + outras_despesas + acrescimo - desconto;
    total_despesas = total_despesas.toFixed(2);
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
        <div class='form-floating form-floating-outline'>
            <select required id='type' name='anexo_fornecedor_${contador}' class='select2 form-select'>
                <option value=''>Opções</option>
                @foreach($fornecedores as $fornecedor)
                    <option value='{{ $fornecedor->id }}'>{{ $fornecedor->nome }}</option>
                @endforeach
            </select>
            <label for='axexo_fornecedor_${contador}'>Fornecedor ${contador}:</label>
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

    if(parseInt(document.getElementById('contador_items').value) <= 0){
        alert('É necessário adicionar pelo menos 1 item!');
        return;
    }

    document.getElementById('formulario').submit();
});
</script>
@endsection
