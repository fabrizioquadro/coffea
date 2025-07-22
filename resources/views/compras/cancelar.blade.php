@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-danger mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Cancelar Compra</h4>
        </div>
        <hr>
        <form action="{{ route('compras.cancelar.set') }}" method="post">
            @csrf
            <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
            <p>Tem certeza que deseja cancalar a compra?</p>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-danger">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
