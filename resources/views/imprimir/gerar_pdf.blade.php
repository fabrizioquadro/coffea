@php
//vamos transformaro logo em base64 para apresentar
$path = public_path('img/unidades/'.$requisicao->unidade->logo);
$logo_base64 = "";
if (Illuminate\Support\Facades\File::exists($path)) {
    $mime = Illuminate\Support\Facades\File::mimeType($path);
    $data = Illuminate\Support\Facades\File::get($path);
    $base64 = base64_encode($data);
    $logo_base64 = "data:{$mime};base64,{$base64}";
}
$var = explode(' ', $requisicao->created_at);
$dt_criacao = dataDbForm($var[0])." ".$var[1];

//vamos descobrir quem executou a validação
$dt_validacao = null;
$hist_val = $requisicao->historicos()->where('status','Em Autorização')->orderBy('id')->first();
if($hist_val){
    $var = explode(' ', $hist_val->created_at);
    $dt_validacao = dataDbForm($var[0])." ".$var[1];
}

$dt_compra = null;
$nm_compra = null;
$hist_compra = $requisicao->historicos()->where('status','PEDIDO COMPRA')->orderBy('id')->first();
if($hist_compra){
    $nm_compra = $hist_compra->user->nome;
    $var = explode(' ', $hist_compra->created_at);
    $dt_compra = dataDbForm($var[0])." ".$var[1];
}

//vamos descobrir quem executou a aprovação
$dt_aprovacao = null;
$hist_aprov = $requisicao->historicos()->where('status','Compra Aprovada')->orderBy('id')->first();
if($hist_aprov){
    $var = explode(' ', $hist_aprov->created_at);
    $dt_aprovacao = dataDbForm($var[0])." ".$var[1];
}

$dt_confirmacao = null;
if($requisicao->qrcode()){
    $var = explode(" ", $requisicao->qrcode()->updated_at);
    $dt_confirmacao = dataDbForm($var[0])." ".$var[1];
}
$portador = $requisicao->portador ? $requisicao->portador : '____________________________________';
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pdf</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style media="screen">
        *{
            padding: 0px;
            margin: 0px;
        }
        .td_info{
            border: 1px solid #696969;
            border-radius: 5px;
            padding: 5px;
        }
        label{
            font-size: 10px;
        }
        b{
            font-size: 10px;
        }
        .f_h3{
            font-size: 16px;
        }
        .f_tabela th{
            font-size: 10px;
            font-weight: 900;
        }

        .f_tabela td{
            font-size: 10px;
            padding-left: 2px;
            padding-right: 2px;
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="padding: 10 5 10 5;">
        <table style="width: 100%">
            <tr>
                <td style="width: 30% !important; text-align: center; vertical-align: middle">
                    <img src="{{ $logo_base64 }}" style="max-height: 100px; max-width: 300px"><br>
                    <h6 style="margin-top: 10px">Unidade: <strong>{{ $requisicao->unidade->nome }}</strong></h6>
                </td>
                <td style="width: 30% !important; text-align: center; vertical-align: middle">
                    @if($qrcode)
                        <img src="{{ $qrcode }}" height="100px"><br>
                        <span style="margin-top: 5px; font-size: 8px">Escanear o QR Code para verificar validade da Requisição</span>
                    @endif
                </td>
                <td style="width: 30% !important; text-align: center; vertical-align: middle">
                    <h6>Requisição:</h6>
                    <h1><strong>{{ $requisicao->id }}</strong></h1>
                </td>
            </tr>
        </table>
        <hr>
        <p style="margin-top: 10px">
            Queiram fornecer Sr.(a) {{ $portador }} as seguintes mercadorias descriminadas.
        </p>
        <table style="width: 100%; margin-top: 5px; margin-bottom: 5px">
            <tr>
                <td colspan="2" class="td_info" style="width: 75%">
                    <label for="fornecedor_id">Fornecedor:</label>
                    <b>{{ $requisicao->fornecedor ? $requisicao->fornecedor->nome : '' }}</b>
                </td>
                <td class="td_info" style="width: 25%">
                    <label for="">Status:</label>
                    <b>{{ $requisicao->status }}</b>
                </td>
            </tr>
        </table>
        <table style="width: 100%; margin-top: 5px; margin-bottom: 5px">
            <tr>
                <td colspan="2" class="td_info" style="width: 60%">
                    <label for="fornecedor_id">Email/Whatsapp Fornecedor:</label>
                    <b>{{ $requisicao->fornecedor_email." ".$requisicao->fornecedor_whatsapp }}</b>
                </td>
                <td class="td_info" style="width: 25%">
                    <label for="data_previa_conclusao">Data Prévia Conclusão:</label>
                    <b>{{ dataDbForm($requisicao->data_previa_conclusao) }}</b>
                </td>
                <td class="td_info" style="width: 15%">
                    <label for="Utilizada">Utilizada:</label>
                    <b>{{ $requisicao->aceito_pelo_fornecedor ? 'Sim '.$dt_confirmacao : 'Não' }}</b>
                </td>
            </tr>
        </table>
        <table style="width: 100%; margin-top: 5px; margin-bottom: 5px">
            <tr>
                <td colspan="2" class="td_info" style="width: 25%">
                    <label for="user_moderador_id">Solicitante:</label>
                    <b>{{ $requisicao->criador->nome }}</b>
                    <b>{{ $dt_criacao }}</b>
                </td>
                <td colspan="2" class="td_info" style="width: 25%">
                    <label for="user_moderador_id">Compra:</label>
                    <b>{{ $nm_compra }}</b>
                    <b>{{ $dt_compra }}</b>
                </td>
                <td class="td_info" style="width: 25%">
                    <label for="user_moderador_id">Validação:</label>
                    <b>{{ $hist_val ? $hist_val->user->nome : '---' }}</b>
                    <b>{{ $dt_validacao }}</b>
                </td>
                <td class="td_info" style="width: 25%">
                    <label for="user_liberador_id">Aprovação:</label>
                    <b>{{ $hist_aprov ? $hist_aprov->user->nome : '---' }}</b>
                    <b>{{ $dt_aprovacao }}</b>
                </td>
            </tr>
        </table>
        <table style="width: 100%; margin-top: 5px; margin-bottom: 5px">
            <tr>
                <td colspan="2" class="td_info" style="width: 20%">
                    <label for="setor_id">Setor:</label>
                    <b>{{ $requisicao->setor->nome }}</b>
                </td>
                <td class="td_info" style="width: 30%">
                    <label for="unidade_id">Unidade:</label>
                    <b>{{ $requisicao->unidade->nome }}</b>
                </td>
                <td class="td_info" style="width: 50%">
                    <label for="unidade_id">Operação:</label>
                    <b style="font-size:12px">{{ $requisicao->financeiros->first() ? $requisicao->financeiros->first()->operacao->descricao : '' }}</b>
                </td>
            </tr>
        </table>
        <table style="width: 100%; margin-top: 5px; margin-bottom: 5px">
            <tr>
                <td colspan="2" class="td_info" style="width: 50%">
                    <label for="qtd_itens_pedido">Total Quantidade:</label>
                    <b>{{ $requisicao->qtd_itens_pedido }}</b>
                </td>
                <td class="td_info" style="width: 50%">
                    <label for="subtotal_pedido">Subtotal Pedido:</label>
                    <b>R$ {{ valorDbForm($requisicao->subtotal_pedido) }}</b>
                </td>
            </tr>
        </table>
        <table style="width: 100%; margin-top: 5px; margin-bottom: 5px">
            <tr>
                <td class="td_info" style="width: 25%">
                    <label for="frete">Frete:</label>
                    <b>R$ {{ valorDbForm($requisicao->frete) }}</b>
                </td>
                <td class="td_info" style="width: 25%">
                    <label for="outras_despesas">Outras Despesas:</label>
                    <b>R$ {{ valorDbForm($requisicao->outras_despesas) }}</b>
                </td>
                <td class="td_info" style="width: 25%">
                    <label for="desconto">Desconto:</label>
                    <b>R$ {{ valorDbForm($requisicao->desconto) }}</b>
                </td>
                <td class="td_info" style="width: 25%">
                    <label for="acrescimo">Acrescimo:</label>
                    <b>R$ {{ valorDbForm($requisicao->acrescimo) }}</b>
                </td>
            </tr>
        </table>
        <table style="width: 100%; margin-top: 5px; margin-bottom: 5px">
            <tr>
                <td class="td_info" style="width: 25%">
                    <label for="qtd_itens_pedido">Total Quantidade:</label>
                    <b>{{ $requisicao->qtd_itens_pedido }}</b>
                </td>
                <td class="td_info" style="width: 25%">
                    <label for="subtotal_pedido">Subtotal Pedido:</label>
                    <b>R$ {{ valorDbForm($requisicao->subtotal_pedido) }}</b>
                </td>
                <td class="td_info" style="width: 50%">
                    <label style="fot-size: 14px" for="total_pedido">Total Pedido:</label>
                    <b style="font-size: 14px">R$ {{ valorDbForm($requisicao->total_pedido) }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="td_info" style="width: 100%">
                    <label for="qtd_itens_pedido">Item/Motivo:</label>
                    <b>{{ $requisicao->motivo_pedido_compra }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="td_info" style="width: 100%">
                    <label for="qtd_itens_pedido">Justificativa:</label>
                    <b>{{ $requisicao->justificativa }}</b>
                </td>
            </tr>
        </table>
        <hr>
        <h3 class="f_h3" style="margin-top: 20px">Itens</h3>
        <table class="f_tabela" style="width: 100%; border: 1px solid black; border-collapse: collapse; margin-bottom: 20px">
            <thead>
                <tr style="border: 1px solid;">
                    <th style="border: 1px solid;">Item</th>
                    <th style="border: 1px solid;">Unidade</th>
                    <th style="border: 1px solid;">Qtd</th>
                    <th style="border: 1px solid;">Unitário</th>
                    <th style="border: 1px solid;">Total</th>
                    <th style="border: 1px solid;">Entrega</th>
                </tr>
            </thead>
            <tbody id="tabela_items">
                @foreach($requisicao->itens as $item)
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid;">{{ $item->item->nome }}</td>
                        <td style="border: 1px solid;">{{ $item->ds_unidade }}</td>
                        <td style="border: 1px solid;">{{ $item->qtd_pedida }}</td>
                        <td style="border: 1px solid;">R${{ valorDbForm($item->valor_unid) }}</td>
                        <td style="border: 1px solid;">R${{ valorDbForm($item->valor_total_pedido) }}</td>
                        <td style="border: 1px solid;">{{ dataDbForm($item->data_previsao_entrega) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <h3 class="f_h3" style="margin-top: 20px">Financeiro</h3>
        <table class="f_tabela" style="width: 100%; border: 1px solid black; border-collapse: collapse; margin-bottom: 20px">
            <thead>
                <tr style="border: 1px solid;">
                    <th style="border: 1px solid;">Tipo</th>
                    <th style="border: 1px solid;">Origem</th>
                    <th style="border: 1px solid;">Descrição</th>
                    <th style="border: 1px solid;">Operação</th>
                    <th style="border: 1px solid;">Vencimento</th>
                    <th style="border: 1px solid;">Valor</th>
                    <th style="border: 1px solid;">Doc</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requisicao->financeiros()->orderBy('vencimento')->get() as $financeiro)
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid;">{{ $financeiro->tipo_pagamento }}</td>
                        <td style="border: 1px solid;">{{ $financeiro->origem }}</td>
                        <td style="border: 1px solid;">{{ $financeiro->descricao }}</td>
                        <td style="border: 1px solid;">{{ $financeiro->operacao->descricao }}</td>
                        <td style="border: 1px solid;">{{ dataDbForm($financeiro->vencimento) }}</td>
                        <td style="border: 1px solid;">R$ {{ valorDbForm($financeiro->valor) }}</td>
                        <td style="border: 1px solid;">{{ $financeiro->doc }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
