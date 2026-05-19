<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracao;
use App\Models\Financeiro;

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
            return json_decode($response, true);
        }
        curl_close($ch);
    }

    public function get_operacao_sisagil(){
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
            "Authorization: Bearer $this->token",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
        } else {
            return json_decode($response, true);
        }
        curl_close($ch);
    }

    public function integra_financeiro($financeiro){
        if($financeiro->requisicao_id != "13267" && !$financeiro->sisagil_id_retorno){
            try {
                //vamos descobrir qual parcela é
                $nr_parcela = Financeiro::where('requisicao_id', $financeiro->requisicao_id)
                ->where('id','<=',$financeiro->id)->count();

                $sinalOperacao = $financeiro->cred_deb == "Débito" ? -1 : 1;
                $dados = [
                    'codigoReferencial' => $financeiro->id,
                    'descricao' => $financeiro->requisicao_id."/".$nr_parcela." ".$financeiro->descricao,
                    'idPessoa' => $financeiro->fornecedor->sisagil_id,
                    'dataOcorrencia' => date('d/m/Y'),
                    'dataVencimento' => dataDbForm($financeiro->vencimento),
                    'valor' => $financeiro->valor,
                    'sinalOperacao' => $sinalOperacao,
                    'pendente' => true,
                    'tipo' => 'CV',
                    'idConta' => $financeiro->conta_pagamento->sisagil_id,
                    'idOperacao' => $financeiro->operacao->sisagil_id,
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
                    dd('aqui');
                    echo 'Erro na requisição: ' . curl_error($ch);
                } else {
                    $data = json_decode($response, true);
                    if($data['result'] == "OK"){
                        $financeiro->sisagil_id_retorno = $data['id'];
                        $financeiro->save();

                        //vamos salvar no historico a integração
                        set_historico($financeiro->requisicao_id,'Integração com sisagil do financeiro ID: '.$financeiro->id, $financeiro->requisicao->status);
                    }
                    //else{
                    //    die('Ocorreu um erro na integração.');
                    //}
                }

                curl_close($ch);

            } catch (\Exception $e) {

            }
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
