@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Adicionar Usuário</h4>
        </div>
        <hr>
        <form action="{{ route('usuarios.insert') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row mt-2 gy-4">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="nome" name="nome"/>
                        <label for="nome">Nome:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="email" id="email" name="email"/>
                        <label for="email">E-mail:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="login" name="login"/>
                        <label for="login">Login:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select required id="type" name='perfil_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($perfis as $perfil)
                                <option value="{{ $perfil->id }}">{{ $perfil->descricao }}</option>
                            @endforeach
                        </select>
                        <label for="type">Tipo de Usuário:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="password" id="password" name="password" />
                        <label for="password">Senha:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline mb-4">
                        <select multiple="" class="form-select h-px-100" id="unidades" name="unidades[]">
                            @foreach($unidades as $unidade)
                                <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                            @endforeach
                        </select>
                        <label for="unidades">Unidades:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline mb-4">
                        <select multiple="" class="form-select h-px-100" id="setores" name="setores[]">
                            @foreach($setores as $setor)
                                <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                            @endforeach
                        </select>
                        <label for="setores">Setores:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="file" id="imagem" name="imagem"/>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
