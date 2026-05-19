@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Visualizar Unidade</h4>
        </div>
        <hr>
        <div class="row mt-2 gy-4 align-items-end">
            <div class="col-md-6 form-group">
                <label for="nome">Nome:</label><br>
                <b>{{ $unidade->nome }}</b>
            </div>
            <div class="col-md-6 form-group">
                <label for="cod_sisagil">Cod. Sisagil:</label><br>
                <b>{{ $unidade->cod_sisagil }}</b>
            </div>
            <div class="col-md-6 form-group">
                <label for="token_sisagil">Token Sisagil:</label><br>
                <b>{{ $unidade->token_sisagil }}</b>
            </div>
            <div class="col-md-6 form-group">
                <label for="usuario_sisagil">Usuário Sisagil:</label><br>
                <b>{{ $unidade->usuario_sisagil }}</b>
            </div>
            <div class="col-md-6 form-group">
                <label for="senha_sisagil">Senha Sisagil:</label><br>
                <b>{{ $unidade->senha_sisagil }}</b>
            </div>
            <div class="col-md-6 form-group">
                <label for="type">Status:</label><br>
                <b>{{ $unidade->status }}</b>
            </div>
            <div class="col-md-6 form-group">
                <label for="type">Restrita:</label><br>
                <b>{{ $unidade->restrita }}</b>
            </div>
        </div>
    </div>
</div>
@endsection
