<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\OperacaoController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\AcessoFornecedorController;
use App\Http\Controllers\FinalizadoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('index');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/esqueceu_senha', [LoginController::class, 'esqueceu_senha'])->name('esqueceu_senha');
Route::post('/recuperar_senha', [LoginController::class, 'recuperar_senha'])->name('recuperar_senha');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/acesso_pedido/{link}', [AcessoFornecedorController::class, 'index'])->name('acesso_fornecedor');
Route::post('/manifestacao_fornecedor', [AcessoFornecedorController::class, 'manifestacao_fornecedor'])->name('manifestacao_fornecedor');
Route::get('/compras/imprimir/{id}', [CompraController::class, 'imprimir'])->name('compras.imprimir');

Route::get('/testeapi', [loginController::class, 'testeapi']);


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/perfil', [DashboardController::class, 'perfil'])->name('perfil');
    Route::get('/alterar_senha', [DashboardController::class, 'alterar_senha'])->name('alterar_senha');
    Route::post('/perfil/atualizar_foto', [DashboardController::class, 'atualizar_foto'])->name('perfil.atualizar_foto');
    Route::post('/perfil/update', [DashboardController::class, 'perfil_update'])->name('perfil.update');
    Route::get('/perfil/resetar_foto_perfil', [DashboardController::class, 'resetar_foto_perfil'])->name('perfil.resetar_foto_perfil');
    Route::post('/alterar_senha/update', [DashboardController::class, 'alterar_senha_update'])->name('alterar_senha.update');

    Route::get('/perfis', [PerfilController::class, 'index'])->name('perfis');
    Route::get('/perfis/adicionar', [PerfilController::class, 'adicionar'])->name('perfis.adicionar');
    Route::get('/perfis/editar/{id}', [PerfilController::class, 'editar'])->name('perfis.editar');
    Route::get('/perfis/excluir/{id}', [PerfilController::class, 'excluir'])->name('perfis.excluir');
    Route::post('/perfis/insert', [PerfilController::class, 'insert'])->name('perfis.insert');
    Route::post('/perfis/update', [PerfilController::class, 'update'])->name('perfis.update');
    Route::post('/perfis/delete', [PerfilController::class, 'delete'])->name('perfis.delete');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::get('/usuarios/adicionar', [UsuarioController::class, 'adicionar'])->name('usuarios.adicionar');
    Route::get('/usuarios/editar/{id}', [UsuarioController::class, 'editar'])->name('usuarios.editar');
    Route::get('/usuarios/excluir/{id}', [UsuarioController::class, 'excluir'])->name('usuarios.excluir');
    Route::get('/usuarios/alterar_senha/{id}', [UsuarioController::class, 'alterar_senha'])->name('usuarios.alterar_senha');
    Route::post('/usuarios/insert', [UsuarioController::class, 'insert'])->name('usuarios.insert');
    Route::post('/usuarios/update', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::post('/usuarios/delete', [UsuarioController::class, 'delete'])->name('usuarios.delete');
    Route::post('/usuarios/alterar_senha_update', [UsuarioController::class, 'alterar_senha_update'])->name('usuarios.alterar_senha.update');

    Route::get('/unidades', [UnidadeController::class, 'index'])->name('unidades');
    Route::get('/unidades/adicionar', [UnidadeController::class, 'adicionar'])->name('unidades.adicionar');
    Route::get('/unidades/testar_token/{id}', [UnidadeController::class, 'testar_token'])->name('unidades.testar_token');
    Route::get('/unidades/editar/{id}', [UnidadeController::class, 'editar'])->name('unidades.editar');
    Route::get('/unidades/excluir/{id}', [UnidadeController::class, 'excluir'])->name('unidades.excluir');
    Route::get('/unidades/visualizar/{id}', [UnidadeController::class, 'visualizar'])->name('unidades.visualizar');
    Route::post('/unidades/insert', [UnidadeController::class, 'insert'])->name('unidades.insert');
    Route::post('/unidades/update', [UnidadeController::class, 'update'])->name('unidades.update');
    Route::post('/unidades/delete', [UnidadeController::class, 'delete'])->name('unidades.delete');

    Route::get('/setores', [SetorController::class, 'index'])->name('setores');
    Route::get('/setores/adicionar', [SetorController::class, 'adicionar'])->name('setores.adicionar');
    Route::get('/setores/editar/{id}', [SetorController::class, 'editar'])->name('setores.editar');
    Route::get('/setores/excluir/{id}', [SetorController::class, 'excluir'])->name('setores.excluir');
    Route::post('/setores/insert', [SetorController::class, 'insert'])->name('setores.insert');
    Route::post('/setores/update', [SetorController::class, 'update'])->name('setores.update');
    Route::post('/setores/delete', [SetorController::class, 'delete'])->name('setores.delete');

    Route::get('/operacoes', [OperacaoController::class, 'index'])->name('operacoes');
    Route::get('/operacoes/adicionar', [OperacaoController::class, 'adicionar'])->name('operacoes.adicionar');
    Route::get('/operacoes/editar/{id}', [OperacaoController::class, 'editar'])->name('operacoes.editar');
    Route::get('/operacoes/excluir/{id}', [OperacaoController::class, 'excluir'])->name('operacoes.excluir');
    Route::post('/operacoes/insert', [OperacaoController::class, 'insert'])->name('operacoes.insert');
    Route::post('/operacoes/update', [OperacaoController::class, 'update'])->name('operacoes.update');
    Route::post('/operacoes/delete', [OperacaoController::class, 'delete'])->name('operacoes.delete');

    Route::get('/fornecedores', [FornecedorController::class, 'index'])->name('fornecedores');
    Route::get('/fornecedores/adicionar', [FornecedorController::class, 'adicionar'])->name('fornecedores.adicionar');
    Route::get('/fornecedores/sincronizar_sisagil', [FornecedorController::class, 'sincronizar_sisagil'])->name('fornecedores.sincronizar_sisagil');
    Route::get('/fornecedores/editar/{id}', [FornecedorController::class, 'editar'])->name('fornecedores.editar');
    Route::get('/fornecedores/excluir/{id}', [FornecedorController::class, 'excluir'])->name('fornecedores.excluir');
    Route::get('/fornecedores/visualizar/{id}', [FornecedorController::class, 'visualizar'])->name('fornecedores.visualizar');
    Route::post('/fornecedores/insert', [FornecedorController::class, 'insert'])->name('fornecedores.insert');
    Route::post('/fornecedores/update', [FornecedorController::class, 'update'])->name('fornecedores.update');
    Route::post('/fornecedores/delete', [FornecedorController::class, 'delete'])->name('fornecedores.delete');

    Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos');
    Route::get('/grupos/adicionar', [GrupoController::class, 'adicionar'])->name('grupos.adicionar');
    Route::get('/grupos/editar/{id}', [GrupoController::class, 'editar'])->name('grupos.editar');
    Route::get('/grupos/excluir/{id}', [GrupoController::class, 'excluir'])->name('grupos.excluir');
    Route::post('/grupos/insert', [GrupoController::class, 'insert'])->name('grupos.insert');
    Route::post('/grupos/update', [GrupoController::class, 'update'])->name('grupos.update');
    Route::post('/grupos/delete', [GrupoController::class, 'delete'])->name('grupos.delete');

    Route::get('/contas', [ContaController::class, 'index'])->name('contas');
    Route::get('/contas/adicionar', [ContaController::class, 'adicionar'])->name('contas.adicionar');
    Route::get('/contas/editar/{id}', [ContaController::class, 'editar'])->name('contas.editar');
    Route::get('/contas/excluir/{id}', [ContaController::class, 'excluir'])->name('contas.excluir');
    Route::post('/contas/insert', [ContaController::class, 'insert'])->name('contas.insert');
    Route::post('/contas/update', [ContaController::class, 'update'])->name('contas.update');
    Route::post('/contas/delete', [ContaController::class, 'delete'])->name('contas.delete');

    Route::get('/itens', [ItemController::class, 'index'])->name('itens');
    Route::get('/itens/adicionar', [ItemController::class, 'adicionar'])->name('itens.adicionar');
    Route::get('/itens/editar/{id}', [ItemController::class, 'editar'])->name('itens.editar');
    Route::get('/itens/excluir/{id}', [ItemController::class, 'excluir'])->name('itens.excluir');
    Route::post('/itens/insert', [ItemController::class, 'insert'])->name('itens.insert');
    Route::post('/itens/update', [ItemController::class, 'update'])->name('itens.update');
    Route::post('/itens/delete', [ItemController::class, 'delete'])->name('itens.delete');
    Route::get('/itens/busca_por_grupo', [ItemController::class, 'busca_por_grupo'])->name('itens.buscarItemsGrupo');

    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos');
    Route::get('/pedidos/adicionar', [PedidoController::class, 'adicionar'])->name('pedidos.adicionar');
    Route::get('/pedidos/editar/{id}', [PedidoController::class, 'editar'])->name('pedidos.editar');
    Route::get('/pedidos/excluir/{id}', [PedidoController::class, 'excluir'])->name('pedidos.excluir');
    Route::get('/pedidos/acessar/{id}', [PedidoController::class, 'acessar'])->name('pedidos.acessar');
    Route::post('/pedidos/insert', [PedidoController::class, 'insert'])->name('pedidos.insert');
    Route::post('/pedidos/update', [PedidoController::class, 'update'])->name('pedidos.update');
    Route::get('/pedidos/itens/insert', [PedidoController::class, 'itens_insert'])->name('pedidos.itens.insert');
    Route::post('/pedidos/preparar_compra', [PedidoController::class, 'preparar_compra'])->name('pedidos.preparar_compra');

    Route::get('/requisicoes', [RequisicaoController::class, 'index'])->name('requisicoes');
    Route::get('/requisicoes/adicionar', [RequisicaoController::class, 'adicionar'])->name('requisicoes.adicionar');
    Route::get('/requisicoes/editar/{id}', [RequisicaoController::class, 'editar'])->name('requisicoes.editar');
    Route::get('/requisicoes/acessar/{id}', [RequisicaoController::class, 'acessar'])->name('requisicoes.acessar');
    Route::get('/requisicoes/excluir/{id}', [RequisicaoController::class, 'excluir'])->name('requisicoes.excluir');
    Route::get('/requisicoes/item/delete', [RequisicaoController::class, 'delete_item'])->name('requisicoes.itens.delete');
    Route::get('/requisicoes/anexo/delete', [RequisicaoController::class, 'delete_anexo'])->name('requisicoes.anexo.delete');
    Route::post('/requisicoes/insert', [RequisicaoController::class, 'insert'])->name('requisicoes.insert');
    Route::post('/requisicoes/update', [RequisicaoController::class, 'update'])->name('requisicoes.update');
    Route::get('/requisicoes/financeiro/delete', [RequisicaoController::class, 'financeiro_delete'])->name('requisicoes.financeiro.delete');
    Route::get('/requisicoes/cancelar/{id}', [RequisicaoController::class, 'cancelar_requisicao'])->name('requisicoes.cancelar');
    Route::get('/requisicoes/retornar_para_compra/{id}', [RequisicaoController::class, 'retornar_para_compra'])->name('requisicoes.retornar_para_compra');
    Route::get('/requisicoes/retornar_para_validacao/{id}', [RequisicaoController::class, 'retornar_para_validacao'])->name('requisicoes.retornar_para_validacao');
    Route::post('/requisicoes/enviar_para_validacao', [RequisicaoController::class, 'enviar_para_validacao'])->name('requisicoes.enviar_para_validacao');
    Route::post('/requisicoes/enviar_para_autorizacao', [RequisicaoController::class, 'enviar_para_autorizacao'])->name('requisicoes.enviar_para_autorizacao');
    Route::post('/requisicoes/autorizar_compra', [RequisicaoController::class, 'autorizar_compra'])->name('requisicoes.autorizar_compra');
    Route::post('/requisicoes/ativar_compra', [RequisicaoController::class, 'ativar_compra'])->name('requisicoes.ativar_compra');

    Route::get('/compras', [CompraController::class, 'index'])->name('compras');
    Route::get('/compras/acessar/{id}', [CompraController::class, 'acessar'])->name('compras.acessar');
    Route::get('/compras/entregas/{id}', [CompraController::class, 'entregas'])->name('compras.entregas');
    Route::get('/compras/cancelar/{id}', [CompraController::class, 'cancelar'])->name('compras.cancelar');
    Route::get('/compras/integrar/{id}', [CompraController::class, 'integrar'])->name('compras.integrar');
    Route::get('/compras/integrar_get_parcela_sisagil/{id}', [CompraController::class, 'get_parcela_sisagil'])->name('compras.integrar.get_parcela_sisagil');
    Route::post('/compras/integrar_set', [CompraController::class, 'integrar_set'])->name('compras.integrar.set');
    Route::post('/compras/cancelar_set', [CompraController::class, 'cancelar_set'])->name('compras.cancelar.set');
    Route::post('/compras/entregas_set', [CompraController::class, 'entregas_set'])->name('compras.entregas.set');

    Route::get('/finalizados', [FinalizadoController::class, 'index'])->name('finalizados');
    Route::get('/finalizados/acessar/{id}', [FinalizadoController::class, 'acessar'])->name('finalizados.acessar');
    Route::get('/finalizados/entregas/{id}', [FinalizadoController::class, 'entregas'])->name('finalizados.entregas');
    Route::get('/finalizados/integrar/{id}', [FinalizadoController::class, 'integrar'])->name('finalizados.integrar');

});
