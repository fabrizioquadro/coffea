@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Editar Unidade</h4>
        </div>
        <hr>
        <form action="{{ route('unidades.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="unidade_id" value="{{ $unidade->id }}">
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="nome" name="nome" value="{{ $unidade->nome }}"/>
                        <label for="nome">Nome:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="number" id="cod_sisagil" name="cod_sisagil" value="{{ $unidade->cod_sisagil }}"/>
                        <label for="cod_sisagil">Cod. Sisagil:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="token_sisagil" name="token_sisagil" value="{{ $unidade->token_sisagil }}"/>
                        <label for="token_sisagil">Token Sisagil:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="usuario_sisagil" name="usuario_sisagil" value="{{ $unidade->usuario_sisagil }}"/>
                        <label for="usuario_sisagil">Usuário Sisagil:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="senha_sisagil" name="senha_sisagil" value="{{ $unidade->senha_sisagil }}"/>
                        <label for="senha_sisagil">Senha Sisagil:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline mt-3">
                        <select required id="type" name='status' class="select2 form-select">
                            <option value="">Opções</option>
                            <option @if($unidade->status == "Ativo") selected @endif value="Ativo">Ativo</option>
                            <option @if($unidade->status == "Inativo") selected @endif value="Inativo">Inativo</option>
                        </select>
                        <label for="type">Status:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="file" id="logo" name="logo"/>
                        <label for="logo">Logo:</label>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <button type="submit" class="btn btn-primary me-2">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
