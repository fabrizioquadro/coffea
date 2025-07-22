@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Adicionar Operação</h4>
        </div>
        <hr>
        <form action="{{ route('operacoes.insert') }}" method="post">
            @csrf
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="descricao" name="descricao"/>
                        <label for="descricao">Descrição:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="number" id="sisagil_id" name="sisagil_id"/>
                        <label for="sisagil_id">Codigo Sisagil:</label>
                    </div>
                </div>
                <div class="col-md-6">
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
                    <div class="form-floating form-floating-outline mt-3">
                        <select required id="operacao_padrao_cancelamento" name='operacao_padrao_cancelamento' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                        <label for="operacao_padrao_cancelamento">Padrão Cancelamento:</label>
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
