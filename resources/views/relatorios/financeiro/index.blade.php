@extends('layout.admin')

@section('conteudo')
<style media="screen">
    .select2-selection__rendered{
        line-height: 40px !important;
        border-color: red !important;
    }
    .select2-selection{
        height: 40px !important;
    }

    .form-select, .input-group-text{
    height: 45px !important;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: none; /* Remove a seta */
  }
</style>
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Relatório Financeiro</h4>
        </div>
        <hr>
        <form action="{{ route('rel_financeiro.gerar') }}" method="post">
            @csrf
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-8">
                    <div class="form-floating form-floating-outline">
                        <select id="operacao_id" name="operacao_id" class="form-select combobox">
                            <option value="">Opções</option>
                            @foreach($operacaos as $operacao)
                                <option value="{{ $operacao->id }}">{{ $operacao->descricao }}</option>
                            @endforeach
                        </select>
                        <label for="operacao_id">Operação:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select id="unidade_id" name="unidade_id" class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($unidades as $unidade)
                                <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                            @endforeach
                        </select>
                        <label for="status">Unidade:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <select id="status" name="status" class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Compra Aprovada">Compra Aprovada</option>
                            <option value="Compra Aprovada (entregue)">Compra Aprovada (entregue)</option>
                            <option value="Compra Aprovada (não entregue)">Compra Aprovada (não entregue)</option>
                            <option value="Compra Finalizada">Compra Finalizada</option>
                        </select>
                        <label for="status">Situação:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <select id="cred_deb" name="cred_deb" class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Crédito">Crédito</option>
                            <option value="Débito">Débito</option>
                        </select>
                        <label for="cred_deb">Cred/Deb:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <select id="origem" name="origem" class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Compra">Compra</option>
                            <option value="Devolução/Cancelamento">Devolução/Cancelamento</option>
                        </select>
                        <label for="origem">Origem:</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating form-floating-outline">
                        <select id="integrado" name="integrado" class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                        <label for="integrado">Integrado:</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_inc" name="dt_inc"/>
                        <label for="dt_inc">Início:</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_fn" name="dt_fn"/>
                        <label for="dt_fn">Fim:</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Gerar</button>
            </div>
        </form>
    </div>
</div>
<script>
window.addEventListener('load',()=>{
    $('.combobox').combobox();
});
</script>
@endsection
