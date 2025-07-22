@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Adicionar Item</h4>
        </div>
        <hr>
        <form action="{{ route('itens.insert') }}" method="post">
            @csrf
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="descricao" name="nome"/>
                        <label for="descricao">Nome:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select required id="type" name='grupo_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id }}">{{ $grupo->descricao }}</option>
                            @endforeach
                        </select>
                        <label for="type">Grupo:</label>
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
