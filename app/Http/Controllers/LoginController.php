<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function index(){
        return view('login/index');
    }

    public function login(Request $request){
        $user = User::where('login',$request->login)->first();
        if($user){
            $dados = [
                'email' => $user->email,
                'password' => $request->password,
            ];

            if(Auth::attempt($dados)){
                $request->session()->regenerate();
                return redirect()->route('dashboard');
            }
            else{
                return redirect()->back()->with('erro', "true");
            }
        }
        else{
            return redirect()->back()->with('erro', "true");
        }
    }

    public function esqueceu_senha(){
        return view('login/esqueceu_senha');
    }

    public function recuperar_senha(Request $request){
        $user = User::where('email', $request->get('email'))->first();
        if($user){
            $novaSenha = createPassword(8, true, true, true, false);
            $user->password = bcrypt($novaSenha);
            $user->save();

            $mensagem = "
            <h4>Nova Senha de Acesso ao Sistema Construtora AvantGarde - Sistema Online</h4>
            <p>
                Foi alterado por sua solicitação a senha de acesso ao sistema.
            </p>
            <p>
                Sua nova senha é: $novaSenha
            </p>
            ";

            enviarMail($user->email, 'Nova Senha de Acesso', $mensagem);

            return redirect()->route('index')->with('sucesso','Sua nova senha foi enviado para o seu email.');
        }
        else{
            return redirect()->back()->with('erro', "Email inválido");
        }
    }

    public function logout(Request $request){
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }

    public function testeapi(){
        //die('teste api desabilitado');
        /*
        echo "Estamos no teste de api <br>";

        $parametros = [
            'action' => 'get',
            'entity' => 'movimento_financeiro',
            'codigoReferencial' => '99998771',
        ];

        $apiUrl = "https://sistema.sischef.com/service?".http_build_query($parametros);
        $bearerToken = "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJBUEkuY29mZmVhLnJoIiwibmJmIjoxNzUwNTk3MzYwfQ.5ZYIqPUecwKib8B6T3hYPaaDtRoqFnI0vJQyMz3zWdPZ0ARlxXK-92eJepFJnxWYmY9lyYHQP0HOk9CJzj7hYA";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Define explicitamente o método GET (opcional)
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $bearerToken",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
        } else {
            $data = json_decode($response, true);
            echo "<pre>";
            print_r($response);
            echo "</pre>";
        }

        curl_close($ch);

        */
        //echo "Estamos no teste de api <br>";
/*
        $conta = [
            'idConta' => 21851,
        ];

        $pessoa = [
            'codigoReferencial' => '782190',
        ];

        $dados = [
            'codigoReferencial' => '99998771',
            'idFormaPagamento' => '34536',
            'descricao' => 'Teste com o rodrigo 10',
            'idPessoa' => '782190',
            'dataOcorrencia' => '24/06/2025',
            'dataVencimento' => '02/07/2025',
            'valor' => 1.85,
            'sinalOperacao' => -1,
            'pendente' => true,
            'tipo' => 'CV',
            'idConta' => 19148,
        ];
*/
        $parametros = [
            'j_username' => 'API.coffea.rh',
            'j_password' => '01010101',
        ];

        $apiUrl = "https://sistema.sisagil.com/inicio.jsf";
        //$bearerToken = "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJBUEkuY29mZmVhLnJoIiwibmJmIjoxNzUwNTk3MzYwfQ.5ZYIqPUecwKib8B6T3hYPaaDtRoqFnI0vJQyMz3zWdPZ0ARlxXK-92eJepFJnxWYmY9lyYHQP0HOk9CJzj7hYA";

        $ch = curl_init($apiUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));

        //curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //    "Authorization: Bearer $bearerToken",
        //]);

        $response = curl_exec($ch);

        echo "<pre>";
        print_r($response);
        echo "</pre>";

        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
        } else {
            $data = json_decode($response, true);
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        }

        curl_close($ch);


    }

}
