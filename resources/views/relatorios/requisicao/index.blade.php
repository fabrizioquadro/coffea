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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Relatório Requisição</h4>
        </div>
        <hr>
        <form action="{{ route('rel_requisicao.gerar') }}" method="post">
            @csrf
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select id="fornecedor_id" name="fornecedor_id" class="select2 form-select">
                            <option value="">Opções</option>
                        </select>
                        <label for="fornecedor_id">Fornecedor:</label>
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
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select id="status" name="status" class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Pedido">Pedidos</option>
                            <option value="Pedido Cancelado">Pedido Cancelado</option>
                            <option value="Em Validação">Em Validação</option>
                            <option value="Em Autorização">Em Autorização</option>
                            <option value="Compra Aprovada">Compra Aprovada</option>
                            <option value="Compra Aprovada (entregue)">Compra Aprovada (entregue)</option>
                            <option value="Compra Aprovada (não entregue)">Compra Aprovada (não entregue)</option>
                            <option value="Compra Cancelada">Compra Cancelada</option>
                            <option value="Compra Finalizada">Compra Finalizada</option>
                        </select>
                        <label for="status">Situação:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" id="dt_inc" name="dt_inc"/>
                        <label for="dt_inc">Início:</label>
                    </div>
                </div>
                <div class="col-md-6">
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
window.addEventListener('load', ()=>{
    $('#fornecedor_id').select2({
        placeholder: "Escolha o Fornecedor.",
        allowClear: true,
        minimumInputLength: 2,
        ajax:{
            url:"{{ route('fornecedores.get_fornecedor_select') }}",
            dataType: "json",
            type: 'GET',
            delay: 250,
            data:function(params){
                return {
                    q: params.term,
                };
            },
            processResults: function(data){
                return {
                    results:data
                };
            },
        cache: true
        }
    });
})
</script>
@endsection
