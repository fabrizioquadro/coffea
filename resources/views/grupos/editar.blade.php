@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Editar Grupo</h4>
        </div>
        <hr>
        <form action="{{ route('grupos.update') }}" method="post">
            @csrf
            <input type="hidden" name="grupo_id" value="{{ $grupo->id }}">
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="descricao" name="descricao" value="{{ $grupo->descricao }}"/>
                        <label for="descricao">Nome:</label>
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
