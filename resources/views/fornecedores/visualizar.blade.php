@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Visualizar Fornecedor</h4>
        </div>
        <hr>
        <div class="row mt-2 gy-4">
            <div class="col-md-6 form-group">
                <label for="nome">Nome:</label><br>
                <b>{{ $fornecedor->nome }}</b>
            </div>
            <div class="col-md-6 form-group">
                <label for="fantasia">Nome Fantasia:</label><br>
                <b>{{ $fornecedor->fantasia }}</b>
            </div>
            <div class="col-md-6 form-group">
                <label for="cpf_cnpj">CPF/CNPJ:</label><br>
                <b>{{ $fornecedor->cpf_cnpj }}</b>
            </div>
            <div class="col-md-6 form-group">
                <label for="sisagil_id">Sisagil ID:</label><br>
                <b>{{ $fornecedor->sisagil_id }}</b>
            </div>
            <div class="col-md-4 form-group">
                <label for="email">Email:</label><br>
                <b>{{ $fornecedor->email }}</b>
            </div>
            <div class="col-md-4 form-group">
                <label for="celular">Celular:</label><br>
                <b>{{ $fornecedor->celular }}</b>
            </div>
            <div class="col-md-4 form-group">
                <label for="cep">CEP:</label><br>
                <b>{{ $fornecedor->cep }}</b>
            </div>
            <div class="col-md-8 form-group">
                <label for="endereco">Endereço:</label><br>
                <b>{{ $fornecedor->endereco }}</b>
            </div>
            <div class="col-md-4 form-group">
                <label for="numero">Número:</label><br>
                <b>{{ $fornecedor->numero }}</b>
            </div>
            <div class="col-md-4 form-group">
                <label for="complemento">Complemento:</label><br>
                <b>{{ $fornecedor->complemento }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="bairro">Bairro:</label><br>
                <b>{{ $fornecedor->bairro }}</b>
            </div>
            <div class="col-md-3 form-group">
                <label for="cidade">Cidade:</label><br>
                <b>{{ $fornecedor->cidade }}</b>
            </div>
            <div class="col-md-2 form-group">
                <label for="uf">UF:</label><br>
                <b>{{ $fornecedor->uf }}</b>
            </div>
        </div>
    </div>
</div>
@endsection
