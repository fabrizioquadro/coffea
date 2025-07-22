@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Adicionar Perfil</h4>
        </div>
        <hr>
        <form action="{{ route('perfis.insert') }}" method="post">
            @csrf
            <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="descricao" name="descricao"/>
                        <label for="descricao">Nome:</label>
                    </div>
                    <div class="form-floating form-floating-outline mt-3">
                        <select required id="type" name='status' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Ativo">Ativo</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                        <label for="type">Status:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="">Acessos:</label>
                    @foreach($opcoes as $opt)
                        <div class="form-check form-check-success">
                            <input class="form-check-input" type="checkbox" value="Sim" id="{{ $opt }}" name="{{ $opt }}">
                            <label class="form-check-label" for="{{ $opt }}" style="text-transform: capitalize"> {{ str_replace('_',' ', $opt) }} </label>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-6 form-group">
                    <button type="submit" class="btn btn-primary me-2">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
