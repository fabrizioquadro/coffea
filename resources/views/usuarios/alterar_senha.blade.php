@extends('layout.admin')

@section('conteudo')
<div class="card card-border-shadow-primary mb-4">
    <h4 class="card-header">Alterar Senha: {{ $usuario->nm_user }}</h4>
    <div class="card-body">
        <form action="{{ route('usuarios.alterar_senha.update') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $usuario->id }}">
            <div class="row mt-2 gy-4">
                <div class="mb-3 col-md-6 form-password-toggle fv-plugins-icon-container">
                    <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="password" name="nova_senha" id="currentPassword">
                            <label for="currentPassword">Nova Senha</label>
                        </div>
                        <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                    </div>
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Salvar</button>
            </div>
        </form>
    </div>
<!-- /Account -->
</div>
@endsection
