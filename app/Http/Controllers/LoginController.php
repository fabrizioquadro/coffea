<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Financeiro;
use App\Models\Requisicao;
use App\Models\Qrcode;
use chillerlan\QRCode\QRCode AS QRCode_gerar;
use chillerlan\QRCode\QROptions;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        $requisicao = Requisicao::where('id','13196')->first();

        $link = $requisicao->id.$requisicao->fornecedor->id.date('YmdHis');
        $link_view   = route('acesso_fornecedor', $link);

        $code = Qrcode::where('requisicao_id', $requisicao->id)->first();

        $qrcode = (new QRCode_gerar)->render($link_view);
        $dados = [
            'requisicao' => $requisicao,
            'link' => $link_view,
            'qrcode' => $qrcode,
            'code' => $code,
        ];
        $html = view('imprimir/gerar_pdf', $dados)->render();

        //vamos gerar o pdf
        $options = new Options();
        //$options->set('isRemoteEnabled', TRUE);
        $options->set('isHtml5ParserEnabled', TRUE);
        //dd($options->get('isHtml5ParserEnabled'));
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("nome_do_arquivo.pdf", array("Attachment" => 0));
        //$output = $dompdf->output();
        //$caminho_arquivo = "public/impressoes/".$requisicao->id.".pdf";
        //file_put_contents($caminho_arquivo, $output);


        /*
        $parametros = [
            'action' => 'list',
            'entity' => 'operacao',
        ];

        $apiUrl = "https://sistema.sischef.com/service?".http_build_query($parametros);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Define explicitamente o método GET (opcional)
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJBUEkuZmF6ZW5kYSIsIm5iZiI6MTc1NzI0NjY1NX0.S_2kfnLn7yHfaUjVgmWd9_R4LVOaJKD4SycMHbPMY4znC8oixJSBzZwZlzxCh4--uw0nHRfKPo8EgPPQOBLEcw",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
        } else {
            echo "<pre>";
            print_r($response);
            echo "</pre>";
        }
        curl_close($ch);
        */
    }

}
