@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-danger mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Cancelar Compra - Cod: {{ $requisicao->id }}</h4>
        </div>
        <hr>
        <form action="{{ route('compras.cancelar.set') }}" method="post">
            @csrf
            <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
            <p>Tem certeza que deseja cancalar a compra?</p>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control h-px-100" id="justificativa_cancelamento" name="justificativa_cancelamento"></textarea>
                        <label for="justificativa_cancelamento">Justificativa:</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-danger">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
