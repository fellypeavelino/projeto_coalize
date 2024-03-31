<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Cliente;
use app\models\Endereco;
use yii\helpers\Json;
use app\services\AuthService;

class ClienteController extends Controller
{
    public $enableCsrfValidation = false; // Desabilitar validação CSRF para a API

    public function actionCadastrar()
    {
        try {
            $request = Yii::$app->request;
            if (!AuthService::validarToken($request)) {
                // Requisição não é do tipo POST
                Yii::$app->response->statusCode = 405; // Method Not Allowed
                return json_encode(['success' => false, 'message' => 'Token Invalido.']);
            }
            // Verificar se é uma requisição POST
            if (!$request->isPost) {
                // Requisição não é do tipo POST
                Yii::$app->response->statusCode = 405; // Method Not Allowed
                return json_encode(['success' => false, 'message' => 'Método não permitido. Utilize POST para cadastrar produtos.']);
            }
            $data = json_decode($request->getRawBody(), true); // Receber os dados JSON
            // Verificar se os dados necessários foram recebidos
            $transaction = Yii::$app->db->beginTransaction(); // Iniciar transação
            if (isset($data['nome'], $data['cpf'], $data['foto'], $data['sexo'], $data['endereco'])) {
                
                $cliente = new Cliente();
                $cliente->nome = $data['nome'];
                $cliente->cpf = $data['cpf'];
                $cliente->foto = $data['foto'];
                $cliente->sexo = $data['sexo'];
                // Salvar o cliente e o endereço
                if ($cliente->save()) {
                    $attributes = $data['endereco'];
                    $result = false;
                    foreach ($attributes as $key => $array) {
                        $obj = (object) $array;
                        $endereco = new Endereco();
                        $endereco->cliente_id = $cliente->id;
                        $endereco->cep = $obj->cep;
                        $endereco->logradouro = $obj->logradouro;
                        $endereco->numero = $obj->numero;
                        $endereco->cidade = $obj->cidade;
                        $endereco->estado = $obj->estado;
                        $endereco->complemento = $obj->complemento;
                        $result = $endereco->save();
                    }
                    if ($result) {
                        // Retorna uma resposta de sucesso
                        $transaction->commit(); // Confirmar transação
                        Yii::$app->response->statusCode = 201; // Created
                        return json_encode(['success' => true, 'message' => 'Cliente cadastrado com sucesso.']);
                    }
                }
                throw new \Exception('Erro ao salvar cliente ou endereço.'); // Lançar exceção se houver erro
            }
        } catch (\Throwable $th) {
            $transaction->rollBack(); // Reverter transação em caso de erro
            Yii::$app->response->statusCode = 400; // Bad Request
            return json_encode(['success' => false, 'message' => $th->getMessage()]);
        }
        // // Se houver erros ou dados faltando, retorna uma resposta de erro
        Yii::$app->response->statusCode = 400; // Bad Request
        return json_encode(['success' => false, 'message' => 'não foram informados todos os dados']);
    }

    public function actionListarClientes()
    {
        try {
            $request = Yii::$app->request;
            if (!AuthService::validarToken($request)) {
                // Requisição não é do tipo POST
                Yii::$app->response->statusCode = 405; // Method Not Allowed
                return json_encode(['success' => false, 'message' => 'Token Invalido.']);
            }
            $clientes = Cliente::find()->all();
    
            return $this->asJson($clientes);
        } catch (\Throwable $th) {
            Yii::$app->response->statusCode = 400; // Bad Request
            return json_encode(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function actionListarClientesEnderecos()
    {
        try {
            $request = Yii::$app->request;
            if (!AuthService::validarToken($request)) {
                // Requisição não é do tipo POST
                Yii::$app->response->statusCode = 405; // Method Not Allowed
                return json_encode(['success' => false, 'message' => 'Token Invalido.']);
            }
            $clientes = Cliente::find()->with('enderecos')->all();
            // Converter os dados para JSON
            $jsonClientes = [];
            foreach ($clientes as $cliente) {
                $enderecos = [];
                foreach ($cliente->enderecos as $key => $obj) {
                    $endereco = [];
                    $endereco['cep'] = $obj->cep;
                    $endereco['logradouro'] = $obj->logradouro;
                    $endereco['numero'] = $obj->numero;
                    $endereco['cidade'] = $obj->cidade;
                    $endereco['estado'] = $obj->estado;
                    $endereco['complemento'] = $obj->complemento;
                    array_push($enderecos, $endereco);
                }
                $jsonClientes[] = [
                    'id' => $cliente->id,
                    'nome' => $cliente->nome,
                    'cpf' => $cliente->cpf,
                    'foto' => $cliente->foto,
                    'sexo' => $cliente->sexo,
                    'enderecos' => $enderecos, // Inclui os endereços no JSON
                ];
            }

            return Json::encode($jsonClientes);
        } catch (\Throwable $th) {
            Yii::$app->response->statusCode = 400; // Bad Request
            return json_encode(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}
