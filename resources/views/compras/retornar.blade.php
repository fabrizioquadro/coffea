@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Retornar Compra - Cod: {{ $requisicao->id }}</h4>
        </div>
        <hr>
        <form action="{{ route('compras.retornar.set') }}" method="post">
            @csrf
            <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select required id="retorno" name='retorno' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Retornado para Validação">Retornar para Moderação</option>
                            <option value="Em Autorização">Retornar para Autorização</option>
                        </select>
                        <label for="retorno">Retornar Para:</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Retornar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
