@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Adicionar Pedido</h4>
        </div>
        <hr>
        <form id="formulario" action="{{ route('pedidos.insert') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="contador_items" id="contador_items" value="0">
            <div class="row mt-2 gy-4 align-items-end">
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
                        <label for="motivo_pedido_compra">Motivo de Uso:</label>
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
                            <th>Obs</th>
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
                <h5 class="modal-title" id="backDropModalTitle">Adicionar Item Pedido</h5>
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
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <input onblur="calcula_total_item()" class="form-control" type="number" id="qtd_pedida" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any"/>
                            <label for="qtd_pedida">Qta Pedida:</label>
                        </div>
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

document.getElementById('botao_adicionar_item').addEventListener('click', ()=>{
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

document.getElementById('botao_salvar_item').addEventListener('click', ()=>{
    item_id = document.getElementById('item_id').value;
    nm_item = $('#item_id').find(":selected").text();
    qtd = document.getElementById('qtd_pedida').value;
    obs = document.getElementById('obs').value;

    if(item_id && qtd){
        contador = parseInt(document.getElementById('contador_items').value);
        contador++;
        document.getElementById('contador_items').value = contador;

        tr = document.createElement('tr');
        td1 = document.createElement('td');
        td2 = document.createElement('td');
        td3 = document.createElement('td');
        td4 = document.createElement('td');

        button = document.createElement('button');
        input1 = document.createElement('input');
        input2 = document.createElement('input');
        input3 = document.createElement('input');

        tr.setAttribute('id', 'linha_item_' + contador);

        td1.innerHTML = nm_item;
        td2.innerHTML = qtd;
        td3.innerHTML = obs;

        button.setAttribute('type', 'button');
        button.setAttribute('onclick', 'excluir_item(' + contador + ')');
        button.setAttribute('class', 'btn rounded-pill btn-icon btn-outline-danger waves-effect');
        button.innerHTML = '<span class="tf-icons mdi mdi-delete"></span>';

        td4.appendChild(button);

        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tr.appendChild(td4);

        input1.setAttribute('type','hidden');
        input1.setAttribute('name','item_id_' + contador);
        input1.setAttribute('value', item_id);

        input2.setAttribute('type','hidden');
        input2.setAttribute('name','qtd_pedida_' + contador);
        input2.setAttribute('class','quantidade');
        input2.setAttribute('value', qtd);

        input3.setAttribute('type','hidden');
        input3.setAttribute('name','obs_' + contador);
        input3.setAttribute('value', obs);


        tr.appendChild(input1);
        tr.appendChild(input2);
        tr.appendChild(input3);

        document.getElementById('tabela_items').appendChild(tr);
        modalItem.hide();
        calcula_total_somatorio();
        document.getElementById('grupo_id').value = '';
        document.getElementById('item_id').innerHTML = '<option value="">Opções</option>';
        document.getElementById('qtd_pedida').value = '';
        document.getElementById('obs').value = '';
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
}

function excluir_item(linha){
    if(confirm('Tem certeza que deseja excluir o item?')){
        document.getElementById('linha_item_' + linha).remove();
        calcula_total_somatorio();
    }
}

document.getElementById('botao_adicionar_requisicao').addEventListener('click', ()=>{

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

    if(parseInt(document.getElementById('contador_items').value) <= 0){
        alert('É necessário adicionar pelo menos 1 item!');
        return;
    }

    document.getElementById('formulario').submit();
});

document.addEventListener("keydown", function(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
  }
});

</script>
@endsection
