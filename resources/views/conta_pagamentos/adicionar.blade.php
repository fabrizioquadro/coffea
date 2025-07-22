@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Adicionar Conta Pagamento</h4>
        </div>
        <hr>
        <form action="{{ route('contas.insert') }}" method="post">
            @csrf
            <div class="row mt-2 gy-4 align-items-end">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <select required id="type" name='unidade_id' class="select2 form-select">
                            <option value="">Opções</option>
                            @foreach($unidades as $unidade)
                                <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                            @endforeach
                        </select>
                        <label for="type">Unidade:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="text" id="descricao" name="descricao"/>
                        <label for="descricao">Descrição:</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline">
                        <input required class="form-control" type="number" id="sisagil_id" name="sisagil_id"/>
                        <label for="sisagil_id">Codigo Sisagil:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select required id="cred_deb" name='cred_deb' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="C">Crédito</option>
                            <option value="D">Débito</option>
                        </select>
                        <label for="cred_deb">Credito/Débito:</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select required id="padrao" name='padrao' class="select2 form-select">
                            <option value="">Opções</option>
                            <option value="N">Não</option>
                            <option value="S">Sim</option>
                        </select>
                        <label for="padrao">Conta Padrão Modalidade:</label>
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
