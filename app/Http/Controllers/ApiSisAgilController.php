<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracao;

class ApiSisAgilController extends Controller
{
    protected $token;

    public function __construct($token){
        $this->token = $token;
    }

    public function testa_token_api(){
        $parametros = [
            'action' => 'get',
            'entity' => 'sistema',
            'dataExpiracao' => 'true',
        ];

        $apiUrl = "https://sistema.sischef.com/service?".http_build_query($parametros);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Define explicitamente o método GET (opcional)
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $this->token",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
        } else {
            echo "Expiração do Token <br>";
            print_r($response);
        }
        curl_close($ch);
    }

    public function get_fornec_sisagil(){
        //dd($this->token);
        $parametros = [
            'action' => 'list',
            'entity' => 'pessoa',
            'tipoFornecedor' => 'true',
            'first' => '0',
            'max' => '9999999999999',
        ];

        $config = Configuracao::where('id','1')->first();
        if($config->dt_ultima_sinc_fornec_sisagil){
            $parametros['ultimaAtualizacao'] = str_replace(' ','-',$config->dt_ultima_sinc_fornec_sisagil);
        }

        $apiUrl = "https://sistema.sischef.com/service?".http_build_query($parametros);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Define explicitamente o método GET (opcional)
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $this->token",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
        } else {
            $config->dt_ultima_sinc_fornec_sisagil = date('Y-m-d H:i:s');
            $config->save();
            return json_decode($response, true);
        }
        curl_close($ch);
    }

    public function integra_financeiro($financeiro){
        try {
            $sinalOperacao = $financeiro->cred_deb == "Débito" ? -1 : 1;
            $dados = [
                'codigoReferencial' => $financeiro->id,
                'descricao' => $financeiro->id." . ".$financeiro->descricao,
                'idPessoa' => $financeiro->fornecedor->sisagil_id,
                'dataOcorrencia' => date('d/m/Y'),
                'dataVencimento' => dataDbForm($financeiro->vencimento),
                'valor' => $financeiro->valor,
                'sinalOperacao' => $sinalOperacao,
                'pendente' => true,
                'tipo' => 'CV',
                'idConta' => $financeiro->conta_pagamento->sisagil_id,
            ];

            $parametros = [
                'action' => 'save',
                'entity' => 'movimento_financeiro',
                'json' => json_encode($dados),
            ];

            $apiUrl = "https://sistema.sischef.com/service";

            $ch = curl_init($apiUrl);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $this->token",
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Erro na requisição: ' . curl_error($ch);
            } else {
                $data = json_decode($response, true);
                if($data['result'] == "OK"){
                    $financeiro->sisagil_id_retorno = $data['id'];
                    $financeiro->save();
                    //$financeiro->requisicao->integrado = true;
                    //$financeiro->requisicao->save();
                }
                //else{
                //    die('Ocorreu um erro na integração.');
                //}
            }

            curl_close($ch);
            
        } catch (\Exception $e) {

        }
    }

    public function get_parcela_sisagil($codigoReferencial){
        $parametros = [
            'action' => 'get',
            'entity' => 'movimento_financeiro',
            'codigoReferencial' => $codigoReferencial,
        ];

        $apiUrl = "https://sistema.sischef.com/service?".http_build_query($parametros);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Define explicitamente o método GET (opcional)
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $this->token",
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
    }

}
