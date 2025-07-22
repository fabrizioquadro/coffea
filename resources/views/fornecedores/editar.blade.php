@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Editar Fornecedor</h4>
        </div>
        <hr>
        <form action="{{ route('fornecedores.update') }}" method="post">
            @csrf
            <input type="hidden" name="fornecedor_id" value="{{ $fornecedor->id }}">
            <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="nome" name="nome" value="{{ $fornecedor->nome }}"/>
                        <label for="nome">Nome:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="fantasia" name="fantasia" value="{{ $fornecedor->fantasia }}"/>
                        <label for="fantasia">Nome Fantasia:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ $fornecedor->cpf_cnpj }}"/>
                        <label for="cpf_cnpj">CPF/CNPJ:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="sisagil_id" name="sisagil_id" value="{{ $fornecedor->sisagil_id }}"/>
                        <label for="sisagil_id">Sisagil ID:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="email" id="email" name="email" value="{{ $fornecedor->email }}"/>
                        <label for="email">Email:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="celular" name="celular" maxlength="15" onkeypress="mascara( this, mtel )" value="{{ $fornecedor->celular }}"/>
                        <label for="celular">Celular:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="cep" name="cep" maxlength="9" onkeypress="formatar('#####-###', this)" value="{{ $fornecedor->cep }}" />
                        <label for="cep">CEP:</label>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="endereco" name="endereco" value="{{ $fornecedor->endereco }}"/>
                        <label for="endereco">Endereço:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="numero" name="numero" value="{{ $fornecedor->numero }}" />
                        <label for="numero">Número:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="complemento" name="complemento" value="{{ $fornecedor->complemento }}" />
                        <label for="complemento">Complemento:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="bairro" name="bairro" value="{{ $fornecedor->bairro }}"/>
                        <label for="bairro">Bairro:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="cidade" name="cidade" value="{{ $fornecedor->cidade }}" />
                        <label for="cidade">Cidade:</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="uf" name="uf" maxlength="2" value="{{ $fornecedor->uf }}" />
                        <label for="uf">UF:</label>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <button type="submit" class="btn btn-primary me-2">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('cep').addEventListener('blur', (e)=>{
    var valor = e.target.value;
    var cep = valor.replace(/\D/g, '');
    if (cep != ""){
        var validacep = /^[0-9]{8}$/;
        if(validacep.test(cep)){
            var url = `https://viacep.com.br/ws/${cep}/json/`;
            fetch(url).then(response => response.json()).then(json => {
                if( json.logradouro ){
                    document.getElementById('endereco').value = json.logradouro;
                    document.getElementById('bairro').value = json.bairro;
                    document.getElementById('cidade').value = json.localidade;
                    document.getElementById('uf').value = json.uf;
                }
            });
        }
    }
});
</script>
@endsection
