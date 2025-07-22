@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Editar Operação</h4>
        </div>
        <hr>
        <form action="{{ route('operacoes.update') }}" method="post">
            @csrf
            <input type="hidden" name="operacao_id" value="{{ $operacao->id }}">
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="descricao" name="descricao" value="{{ $operacao->descricao }}"/>
                        <label for="descricao">Descrição:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="number" id="sisagil_id" name="sisagil_id" value="{{ $operacao->sisagil_id }}"/>
                        <label for="sisagil_id">Codigo Sisagil:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline mt-3">
                        <select required id="type" name='status' class="select2 form-select">
                            <option value="">Opções</option>
                            <option @if($operacao->status == "Ativo") selected @endif value="Ativo">Ativo</option>
                            <option @if($operacao->status == "Inativo") selected @endif value="Inativo">Inativo</option>
                        </select>
                        <label for="type">Status:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline mt-3">
                        <select required id="operacao_padrao_cancelamento" name='operacao_padrao_cancelamento' class="select2 form-select">
                            <option value="">Opções</option>
                            <option @if($operacao->operacao_padrao_cancelamento == "Sim") selected @endif value="Sim">Sim</option>
                            <option @if($operacao->operacao_padrao_cancelamento == "Não") selected @endif value="Não">Não</option>
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
