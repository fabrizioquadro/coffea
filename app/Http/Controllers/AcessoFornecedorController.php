<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;
use App\Models\Financeiro;
use App\Models\Token;
use App\Models\Qrcode AS Code;
use chillerlan\QRCode\{QRCode, QROptions};

class AcessoFornecedorController extends Controller
{
    public function index($link){
        $ip_leitura = request()->server('REMOTE_ADDR');

        $qrcode = Code::where('link', $link)->first();
        //if(!$qrcode || strtotime(date('Y-m-d H:i:s')) > strtotime($qrcode->vencimento)){
        //    die('Pagina Expirada ou inválida!!');
        //}
        $qrcode->ip_ultima_leitura = $ip_leitura;
        $qrcode->data_ultima_leitura = date("Y-m-d H:i:s");
        $qrcode->save();

        $requisicao = Requisicao::where('id', $qrcode->requisicao_id)->first();
        $link   = route('acesso_fornecedor', $requisicao->qrcode()->link);

        $qrcode = (new QRCode)->render($link);
        return view('acesso_fornecedor/index', compact('requisicao','qrcode','link'));
    }

    public function manifestacao_fornecedor(Request $request){
        $requisicao = Requisicao::where('id', $request->requisicao_id)->first();
        if($requisicao->qrcode()->link == $request->validacao){

            //vamos verificar se o codigo de atiação é o correto
            $token = Token::where('requisicao_id', $requisicao->id)->first();
            //if($token->verificador == $request->codigo_validacao){
                if($request->acao == "Aceitar"){
                    $requisicao->aceito_pelo_fornecedor = true;
                    $requisicao->data_manifestacao_fornecedor = date('Y-m-d H:i:s');
                    $requisicao->save();

                    $qrcode = $requisicao->qrcode();
                    $qrcode->aceite_fornecedor = "Aceito";
                    $qrcode->manifestacao_fornecedor = date('Y-m-d H:i:s');
                    $qrcode->save();

                    //vamos buscar os financeiros com pagamento antecipado
                    foreach($requisicao->financeiros as $financeiro){
                        if($financeiro->tipo_pagamento == "Pagamento Antecipado"){
                            $api = new ApiSisAgilController($requisicao->unidade->token_sisagil);
                            $api->integra_financeiro($financeiro);
                        }
                    }

                    return redirect()->route('acesso_fornecedor', $qrcode->link)->with('mensagem' ,'Pedido Aceite');
                }
                elseif($request->acao == "Rejeitar"){
                    $requisicao->aceito_pelo_fornecedor = false;
                    $requisicao->data_manifestacao_fornecedor = date('Y-m-d H:i:s');
                    $requisicao->save();

                    $qrcode = $requisicao->qrcode();
                    $qrcode->aceite_fornecedor = "Rejeitado";
                    $qrcode->manifestacao_fornecedor = date('Y-m-d H:i:s');
                    $qrcode->save();

                    return redirect()->route('acesso_fornecedor', $qrcode->link)->with('mensagem_erro' ,'Pedido Rejeitado');
                }
            //}
            //else{
            //    die('Codigo de validação inválido!');
            //}
        }
        else{
            die('Codigo de validação inválido!');
        }
    }

    public function lançar_financeiro_sisagil($financeiro){
        try{
            $sinalOperacao = $financeiro->cred_deb == "Débito" ? -1 : 1;
            $bearerToken = $financeiro->requisicao->unidade->token_sisagil;
            $pessoa = [
                'codigoReferencial' => '',
            ];

            $dados = [
                'codigoReferencial' => $financeiro->id,
                'descricao' => $financeiro->descricao,
                'pessoa' => $pessoa,
                'dataOcorrencia' => dataDbForm($financeiro->vencimento),
                'dataVencimento' => dataDbForm($financeiro->vencimento),
                'valor' => $financeiro->valor,
                'sinalOperacao' => $sinalOperacao,
                'pendente' => false,
                'tipo' => 'AV',
                'idConta' => $financeiro->conta_pagamento->sisagil_id,
            ];

            /*
            $dados = [
                'codigoReferencial' => '9999999',
                'descricao' => 'Teste Teste - Jul/2025',
                'dataOcorrencia' => '18/06/2025',
                'valor' => 1.00,
                'sinalOperacao' => 1,
                'pendente' => false,
                'tipo' => 'AV',
                'idConta' => 21851,
            ];

            */

            $parametros = [
                'action' => 'save',
                'entity' => 'movimento_financeiro',
                'json' => json_encode($dados),
            ];

            $ch = curl_init(get_url_sisagil_api());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $bearerToken",
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Erro na requisição, contate o administrativo repassando o erro: <br>' . curl_error($ch);
            }
            else{
                $data = json_decode($response, true);
                if($data['result'] == "OK"){
                    $financeiro->sisagil_id_retorno = $data['id'];
                    $financeiro->save();
                }
                else{
                    die('Erro de retorno da api');
                }
            }
        }catch(\Exception $e){
            echo "Ocorreu um erro inesperado!!! <br>";
            echo $e->getMessage();
        }
    }
}
