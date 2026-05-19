<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Perfil;
use App\Models\User;
use App\Models\Configuracao;

class userTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //vamos criar o perfil inicialmente
        $dados = [
            'descricao' => 'Administrador',
            'administrador' => true,
            'criar' => true,
            'preparar_compra' => true,
            'duplicar_pedido_compra' => true,
            'moderar' => true,
            'aprovar' => true,
            'confirmar_recebimento' => true,
            'alterar_qtd_recebimento' => true,
            'editar' => true,
            'corrigir' => true,
            'cancelar' => true,
            'acompanhar' => true,
            'status' => 'ativo',
        ];
        $perfil = Perfil::create($dados);
        $dados = [
            'perfil_id' => $perfil->id,
            'nome' => 'Administrador',
            'email' => 'fabrizio.quadro@gmail.com',
            'login' => 'admin',
            'password' => bcrypt('supporto@2025'),
        ];
        User::create($dados);

        $dados = ['1',''];

        Configuracao::create($dados);
    }
}
