@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Editar Usuário</h4>
        </div>
        <hr>
        <form action="{{ route('usuarios.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $usuario->id }}">
            <div class="row mt-2 gy-4">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="nome" name="nome" value="{{ $usuario->nome }}"/>
                        <label for="nome">Nome:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="email" id="email" name="email" value="{{ $usuario->email }}"/>
                        <label for="email">E-mail:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="login" name="login" value="{{ $usuario->login }}"/>
                        <label for="login">Login:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select required id="type" name='perfil_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($perfis as $perfil)
                                <option @if($usuario->perfil_id == $perfil->id) selected @endif value="{{ $perfil->id }}">{{ $perfil->descricao }}</option>
                            @endforeach
                        </select>
                        <label for="type">Tipo de Usuário:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="file" id="imagem" name="imagem"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline mb-4">
                        <select multiple="" class="form-select h-px-100" id="unidades" name="unidades[]">
                            @foreach($unidades as $unidade)
                                <option @if($usuario->unidades()->where('unidade_id', $unidade->id)->count() > 0) selected @endif value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                            @endforeach
                        </select>
                        <label for="unidades">Unidades:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline mb-4">
                        <select multiple="" class="form-select h-px-100" id="setores" name="setores[]">
                            @foreach($setores as $setor)
                                <option @if($usuario->setores()->where('setor_id', $setor->id)->count() > 0) selected @endif value="{{ $setor->id }}">{{ $setor->nome }}</option>
                            @endforeach
                        </select>
                        <label for="setores">Setores:</label>
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
