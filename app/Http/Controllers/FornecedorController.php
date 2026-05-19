<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedor;
use App\Models\Unidade;
use App\Models\Configuracao;

class FornecedorController extends Controller
{
    public function index(){
        $fornecedores = Fornecedor::all();
        return view('fornecedores/index', compact('fornecedores'));
    }

    public function adicionar(){
        return view('fornecedores/adicionar');
    }

    public function insert(Request $request){
        try{
            $dados = $request->except('_token');
            Fornecedor::create($dados);

            return redirect()->route('fornecedores')->with('mensagem','Fornecedor Cadastrado!');
        }catch(\Exception $e){
            return redirect()->route('fornecedores')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function editar($id){
        $fornecedor = Fornecedor::where('id', $id)->first();
        return view('fornecedores/editar', compact('fornecedor'));
    }

    public function update(Request $request){
        try{
            $dados = $request->except('_token','fornecedor_id');
            Fornecedor::where('id', $request->fornecedor_id)->update($dados);
            return redirect()->route('fornecedores')->with('mensagem', 'Fornecedor Editado!');
        }catch(\Exception $e){
            return redirect()->route('fornecedores')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function excluir($id){
        $fornecedor = Fornecedor::where('id', $id)->first();
        return view('fornecedores/excluir', compact('fornecedor'));
    }

    public function delete(Request $request){
        try {
            Fornecedor::where('id', $request->fornecedor_id)->delete();
            return redirect()->route('fornecedores')->with('mensagem', 'Fornecedor Excluído');
        } catch (\Exception $e) {
            return redirect()->route('fornecedores')->with('mensagem_erro', $e->getMessage());
        }
    }

    public function visualizar($id){
        $fornecedor = Fornecedor::where('id', $id)->first();
        return view('fornecedores/visualizar', compact('fornecedor'));
    }

    public function sincronizar_sisagil(){
        $unidade_geral = Unidade::where('restrita','Não')->first();
        $unidades_restritas = Unidade::where('restrita','Sim')->get();

        //vamos começar pelas unidades gerais
        $api_sisagil = new ApiSisAgilController($unidade_geral->token_sisagil);
        $fornecs = $api_sisagil->get_fornec_sisagil();

        foreach($fornecs as $fornec){
            $fantasia = isset($fornec['nomeFantasia']) ? $fornec['nomeFantasia'] : $fornec['nome'];
            $cidade = $fornec['municipio'] ? $fornec['municipio'] : array();
            $uf = $fornec['estado'] ? $fornec['estado'] : array();
            $dados = [
                'sisagil_id' => $fornec['id'] ? $fornec['id'] : null,
                'nome' => @$fornec['nome'] ? $fornec['nome'] : null,
                'fantasia' => $fantasia,
                'cpf_cnpj' => @$fornec['cpfCnpj'] ? $fornec['cpfCnpj'] : null,
                'endereco' => @$fornec['endereco'] ? $fornec['endereco'] : null,
                'numero' => @$fornec['numero'] ? $fornec['numero'] : null,
                'bairro' => @$fornec['bairro'] ? $fornec['bairro'] : null,
                'complemento' => @$fornec['complemento'] ? $fornec['complemento'] : null,
                'cidade' => @$cidade['nome'] ? $cidade['nome'] : null,
                'uf' => @$uf['sigla'] ? $uf['sigla'] : null,
                'celular' => @$fornec['celular'] ? $fornec['celular'] : null,
                'celular' => @$fornec['celular'] ? $fornec['celular'] : null,
                'email' => @$fornec['email'] ? $fornec['email'] : null,
                'cep' => @$fornec['cep'] ? $fornec['cep'] : null,
                'status' => @$fornec['ativo'] ? 'Ativo' : 'Inativo',
            ];

            $fornecedor = Fornecedor::where('sisagil_id', $fornec['id'])->first();
            if(!$fornecedor){
                Fornecedor::create($dados);
            }
            else{
                Fornecedor::where('id', $fornecedor->id)->update($dados);
            }
        }

        foreach($unidades_restritas as $unidade){
            $api_sisagil = new ApiSisAgilController($unidade->token_sisagil);
            $fornecs = $api_sisagil->get_fornec_sisagil();

            foreach($fornecs as $fornec){
                $fantasia = isset($fornec['nomeFantasia']) ? $fornec['nomeFantasia'] : $fornec['nome'];
                $cidade = $fornec['municipio'] ? $fornec['municipio'] : array();
                $uf = $fornec['estado'] ? $fornec['estado'] : array();
                $dados = [
                    'sisagil_id' => $fornec['id'] ? $fornec['id'] : null,
                    'nome' => @$fornec['nome'] ? $fornec['nome'] : null,
                    'fantasia' => $fantasia,
                    'cpf_cnpj' => @$fornec['cpfCnpj'] ? $fornec['cpfCnpj'] : null,
                    'endereco' => @$fornec['endereco'] ? $fornec['endereco'] : null,
                    'numero' => @$fornec['numero'] ? $fornec['numero'] : null,
                    'bairro' => @$fornec['bairro'] ? $fornec['bairro'] : null,
                    'complemento' => @$fornec['complemento'] ? $fornec['complemento'] : null,
                    'cidade' => @$cidade['nome'] ? $cidade['nome'] : null,
                    'uf' => @$uf['sigla'] ? $uf['sigla'] : null,
                    'celular' => @$fornec['celular'] ? $fornec['celular'] : null,
                    'celular' => @$fornec['celular'] ? $fornec['celular'] : null,
                    'email' => @$fornec['email'] ? $fornec['email'] : null,
                    'cep' => @$fornec['cep'] ? $fornec['cep'] : null,
                    'unidade_id' => $unidade->id,
                    'status' => @$fornec['ativo'] ? 'Ativo' : 'Inativo',
                ];

                $fornecedor = Fornecedor::where('sisagil_id', $fornec['id'])->first();
                if(!$fornecedor){
                    Fornecedor::create($dados);
                }
                else{
                    Fornecedor::where('id', $fornecedor->id)->update($dados);
                }
            }
        }

        $dados = [
            'dt_ultima_sinc_fornec_sisagil' => date('Y-m-d H:i:s')
        ];

        Configuracao::where('id','1')->update($dados);

        return redirect()->route('fornecedores')->with('mensagem', 'Sincronização efetuada!');
    }

    public function get_fornecedor_select(){
        if(isset($_GET['q'])){
            $procurar = $_GET['q'];
            $fornecedores = Fornecedor::where('nome','LIKE','%'.$procurar.'%')->get();
            $array_retorno = array();
            foreach($fornecedores as $fornec){
                $array = [
                    'id' => $fornec->id,
                    'text' => $fornec->nome,
                ];
                $array_retorno[] = $array;
            }

            echo json_encode($array_retorno);

        }
    }

}
