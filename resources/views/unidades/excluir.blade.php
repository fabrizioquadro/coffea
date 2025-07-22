@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-danger mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Excluir Unidade</h4>
        </div>
        <hr>
        <form action="{{ route('unidades.delete') }}" method="post">
            @csrf
            <input type="hidden" name="unidade_id" value="{{ $unidade->id }}">
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-12">
                    <p>Tem certeza que deseja excluir a unidade {{ $unidade->nome }}?</p>
                </div>
                <div class="col-md-6 form-group">
                    <button type="submit" class="btn btn-danger me-2">Excluir</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
