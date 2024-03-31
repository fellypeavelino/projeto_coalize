<?php

namespace app\controllers\api;

use Yii;
use yii\web\Controller;
use app\models\Cliente;
use app\models\Endereco;
use yii\helpers\Json;

class ClienteController extends Controller
{
    public $enableCsrfValidation = false; // Desabilitar validação CSRF para a API

    public function actionCadastrar()
    {
        $request = Yii::$app->request;
        $data = json_decode($request->getRawBody(), true); // Receber os dados JSON
        // Verificar se os dados necessários foram recebidos
        if (isset($data['nome'], $data['cpf'], $data['foto'], $data['sexo'], $data['endereco'])) {
            $transaction = Yii::$app->db->beginTransaction(); // Iniciar transação
            try {
                $cliente = new Cliente();
                $cliente->nome = $data['nome'];
                $cliente->cpf = $data['cpf'];
                $cliente->foto = $data['foto'];
                $cliente->sexo = $data['sexo'];
                // Salvar o cliente e o endereço
                if ($cliente->save()) {
                    $attributes = $data['endereco'];
                    // $endereco = new Endereco();
                    // $endereco->cliente_id = $cliente->id;
                    // $endereco->attributes = $data['endereco'];
                    // if ($endereco->save()) {
                    //         // Retorna uma resposta de sucesso
                    //         Yii::$app->response->statusCode = 201; // Created
                    //         return json_encode(['success' => true, 'message' => 'Cliente cadastrado com sucesso.']);
                    // }
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
            } catch (\Exception $e) {
                $transaction->rollBack(); // Reverter transação em caso de erro
            }
        }

        // // Se houver erros ou dados faltando, retorna uma resposta de erro
        Yii::$app->response->statusCode = 400; // Bad Request
        return json_encode(['success' => false, 'message' => 'Erro ao cadastrar cliente. Verifique os dados enviados.']);
    }

    public function actionListarClientes()
    {
        $clientes = Cliente::find()->all();

        return $this->asJson($clientes);
    }

    public function actionListarClientesEnderecos()
    {
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
    }
}
