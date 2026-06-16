@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Adicionar Pedido</h4>
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
        <form id="formulario" action="{{ route('pedidos.insert') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="contador_items" id="contador_items" value="0">
            <input type="hidden" name="contador_anexos_gerais" id="contador_anexos_gerais" value="0">
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
                        <textarea onblur="verifica_motivo_pedido_compra(this)" class="form-control h-px-100" id="motivo_pedido_compra" name="motivo_pedido_compra"></textarea>
                        <label for="motivo_pedido_compra">Item / Motivo:</label>
                    </div>
                </div>
                <div class="col-md-12" style='display: none'>
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control h-px-100" id="justificativa" name="justificativa"></textarea>
                        <label for="justificativa">Justificativa:</label>
                      </div>
                </div>
            </div>
            <hr>
            <div style="display:none !important" class="d-flex justify-content-between mt-3 mb-3">
                <h5 class="card-title">Itens</h5>
                <button class="btn btn-sm btn-primary" type="button" id="botao_adicionar_item">Adicionar Item</button>
            </div>
            <div style="display:none !important" class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Unidade</th>
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
            <div style="display:none !important" class="row mt-2 gy-4 align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input onblur="calcula_total_somatorio()" class="form-control" type="text" id="qtd_itens_pedido" name="qtd_itens_pedido" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any"/>
                        <label for="qtd_itens_pedido">Total Quantidade:</label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between mt-3 mb-3">
                <h5 class="card-title">Anexos Gerais</h5>
                <button class="btn btn-sm btn-primary" type="button" id="botao_adicionar_anexo_geral">Adicionar Anexo Geral</button>
            </div>
            <div id="div_anexos_gerais">
                <!-- Anexos gerais aqui -->
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

document.getElementById('botao_salvar_item').addEventListener('click', ()=>{
    item_id = document.getElementById('item_id').value;
    nm_item = $('#item_id').find(":selected").text();
    qtd = document.getElementById('qtd_pedida').value;
    ds_unidade = document.getElementById('ds_unidade').value;
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
        td_unidade = document.createElement('td');

        button = document.createElement('button');
        input1 = document.createElement('input');
        input2 = document.createElement('input');
        input3 = document.createElement('input');
        input_unidade = document.createElement('input');

        tr.setAttribute('id', 'linha_item_' + contador);

        td1.innerHTML = nm_item;
        td2.innerHTML = qtd;
        td3.innerHTML = obs;
        td_unidade.innerHTML = ds_unidade;

        button.setAttribute('type', 'button');
        button.setAttribute('onclick', 'excluir_item(' + contador + ')');
        button.setAttribute('class', 'btn rounded-pill btn-icon btn-outline-danger waves-effect');
        button.innerHTML = '<span class="tf-icons mdi mdi-delete"></span>';

        td4.appendChild(button);

        tr.appendChild(td1);
        tr.appendChild(td_unidade);
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

        input_unidade.setAttribute('type','hidden');
        input_unidade.setAttribute('name','ds_unidade_' + contador);
        input_unidade.setAttribute('value', ds_unidade);


        tr.appendChild(input1);
        tr.appendChild(input2);
        tr.appendChild(input3);
        tr.appendChild(input_unidade);

        document.getElementById('tabela_items').appendChild(tr);
        modalItem.hide();
        calcula_total_somatorio();
        document.getElementById('grupo_id').value = '';
        document.getElementById('item_id').innerHTML = '<option value="">Opções</option>';
        document.getElementById('qtd_pedida').value = '';
        document.getElementById('obs').value = '';
        document.getElementById('ds_unidade').value = '';
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

    //if(parseInt(document.getElementById('contador_items').value) <= 0){
    //    alert('É necessário adicionar pelo menos 1 item!');
    //    return;
    //}

    document.getElementById('formulario').submit();
});

document.addEventListener("keydown", function(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
  }
});

function verifica_motivo_pedido_compra(e){
    if(e.value){
        $.getJSON(
            "{{ route('pedidos.verifica_motivo_compra') }}",
            {
                motivo : e.value
            },
            function(json){
                if(json.controle == 'true'){
                    alert('Motivo de Compra já cadastrado no sistema, não há problema na duplicidade, este é só um aviso de controle.');
                }
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

</script>
@endsection
