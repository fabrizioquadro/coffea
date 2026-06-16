<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Historico;
use App\Models\Alerta;

if(!function_exists('enviarWhatsapp')){
    function enviarWhatsapp($numero, $mensagem){
        //sua key e token do chatmix
        $key = 'REQUISICAO-SUPPORTOTRADING-COM-BR-6FF1';
        $token = '3D64-9FE-2ACFB-5CC3';

        // URL do endpoint
        $baseUrl = "https://disparo.bulkv2.chatmix.com.br/api";

        $numero = str_replace(' ','',$numero);
        $numero = str_replace('(','',$numero);
        $numero = str_replace(')','',$numero);
        $numero = str_replace('-','',$numero);

        $params = [
            'key' => $key,
            'token' => $token,
            'numero' => '55'.$numero,
            'message' => $mensagem,
            'gerar_pdf' => true,
            'ignore_duplicated' => true, // Ignora mensagens duplicadas para o mesmo número
        ];

        $url = $baseUrl .'?'.http_build_query($params);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($ch);

        //echo "<pre>";
        //print_r($response);
        //echo "</pre>";
    }
}


if(!function_exists('get_url_sisagil_api')){
    function get_url_sisagil_api(){
        return "https://sistema.sischef.com/service";
    }
}

if(!function_exists('cadastra_alerta')){
    function cadastra_alerta($dados){
        $dados['visualizacao'] = 'Não';
        Alerta::create($dados);
    }
}

if(!function_exists('set_historico')){
    function set_historico($requisicao_id, $ds_historico, $status){
        $dados = [
            'requisicao_id' => $requisicao_id,
            'user_id' => auth()->user()->id,
            'dt_historico' => date('Y-m-d'),
            'ds_historico' => $ds_historico,
            'status' => $status,
        ];
        Historico::create($dados);
    }
}

if(!function_exists('data_escrita')){
    function data_escrita($data){
        $var = explode('-', $data);
        $ano = $var[0];
        $mes = $var[1];
        $dia = $var[2];

        if($mes == '1' || $mes == '01'){
            $mes = 'janeiro';
        }
        elseif($mes == '2' || $mes == '02'){
            $mes = 'fevereiro';
        }
        elseif($mes == '3' || $mes == '03'){
            $mes = 'março';
        }
        elseif($mes == '4' || $mes == '04'){
            $mes = 'abril';
        }
        elseif($mes == '5' || $mes == '05'){
            $mes = 'maio';
        }
        elseif($mes == '6' || $mes == '06'){
            $mes = 'junho';
        }
        elseif($mes == '7' || $mes == '07'){
            $mes = 'julho';
        }
        elseif($mes == '8' || $mes == '08'){
            $mes = 'agosto';
        }
        elseif($mes == '9' || $mes == '09'){
            $mes = 'setembro';
        }
        elseif($mes == '10'){
            $mes = 'outubro';
        }
        elseif($mes == '11'){
            $mes = 'novembro';
        }
        elseif($mes == '12'){
            $mes = 'dezembro';
        }

        $array_semana = ['domingo','segunda-feira','terça-feira','quarta-feira','quinta-feira','sexta-feira','sábado'];
        $dia_semana = date('w', strtotime($data));

        return $array_semana[$dia_semana]." $dia de $mes de $ano";
    }
}

if(!function_exists('createPassword')){
    function createPassword($tamanho, $maiusculas, $minusculas, $numeros, $simbolos){
        $senha = "";
        $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
        $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
        $nu = "0123456789"; // $nu contem os números
        $si = "!@#$%¨&*()_+="; // $si contem os símbolos

        if ($maiusculas){
            // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($ma);
        }

        if ($minusculas){
            // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($mi);
        }

        if ($numeros){
            // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($nu);
        }

        if ($simbolos){
            // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($si);
        }

        // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
        return substr(str_shuffle($senha),0,$tamanho);

    }
}

if(!function_exists('dataDbForm')){
    function dataDbForm($data){
        if(!$data){
            return null;
        }
        $data = explode("-", $data);
        $data = $data[2]."/".$data[1]."/".$data[0];
        return $data;
    }
}

if(!function_exists('dataFormDb')){
    function dataFormDb($data){
        $data = explode("/", $data);
        $data = $data[2]."-".$data[1]."-".$data[0];
        return $data;
    }
}

if(!function_exists('valorFormDbOld')){
    function valorFormDbOld($valor){
        //vamos procurar se foi digitado a ,
        $virgula = strpos($valor, ',');

        if($virgula === false){
            $valor = str_replace(".","",$valor);
            $valor = $valor.".00";
            return $valor;
        }

        $var = explode(',', $valor);
        $variavel = $var[1];
        $var = str_replace('.', '', $var[0]);
        $valor = $var.'.'.$variavel[0].$variavel[1];
        return $valor;
    }
}

if(!function_exists('valorFormDb')){
    function valorFormDb($valor){
        //vamos procurar se foi digitado a ,
        $virgula = strpos($valor, ',');

        if($virgula === false){
            $valor = str_replace(".","",$valor);
            $valor = $valor.".0000";
            return $valor;
        }

        $var = explode(',', $valor);
        $decimal = $var[1];
        $thousand = str_replace('.', '', $var[0]);
        $decimal_retorno = "";
        for($i=0 ; $i<4 ; $i++){
            if($decimal[$i]){
                $decimal_retorno = $decimal_retorno.$decimal[$i];
            }else{
                $decimal_retorno = $decimal_retorno.'0';
            }
        }
        $valor = $thousand.'.'.$decimal_retorno;
        return $valor;
    }
}

if(!function_exists('valorDbForm')){
    function valorDbForm($valor){
        return number_format($valor,4,",",".");
    }
}

if(!function_exists('enviarMail')){
    function enviarMail($destinatario, $assunto, $mensagem, $arquivo = null){
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->setLanguage('br');
            $mail->CharSet = "utf8";
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.site.com.br/';
            $mail->SMTPAuth = true;
            $mail->Username = 'sistema@wobrasil.com.br';
            $mail->Password = 'wo123456BR';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->FromName = "Grupo Coffea - Sistema de Solicitações";
            $mail->From = "sistema@wobrasil.com.br";
            $mail->IsHTML(true);
            $mail->Subject = $assunto;
            $mail->Body = $mensagem;
            $mail->AddAddress($destinatario);
            if($arquivo){
                $mail->addAttachment($arquivo);
            }
            $mail->Send();
        }
        catch (Exception $e) {
            // die($mail->ErrorInfo);
            return false;
        }
        return true;
    }
}
?>
