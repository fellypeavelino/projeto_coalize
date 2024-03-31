<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Produto;
use yii\helpers\Json;
use app\services\AuthService;

class ProdutoController extends Controller
{
    public $enableCsrfValidation = false; // Desabilitar validação CSRF para a API

    private function validacaoToken($request){
        $headers = $request->headers;
        $authorizationHeader = $headers->get('Authorization');
        var_dump($authorizationHeader);
    }

    public function actionCadastrar()
    {
        $request = Yii::$app->request;
        // Verificar se é uma requisição POST
        if (!$request->isPost) {
            // Requisição não é do tipo POST
            Yii::$app->response->statusCode = 405; // Method Not Allowed
            return json_encode(['success' => false, 'message' => 'Método não permitido. Utilize POST para cadastrar produtos.']);
        }
        try {
            if (!AuthService::validarToken($request)) {
                // Requisição não é do tipo POST
                Yii::$app->response->statusCode = 405; // Method Not Allowed
                return json_encode(['success' => false, 'message' => 'Token Invalido.']);
            }
            $transaction = Yii::$app->db->beginTransaction(); // Iniciar transação
            // Receber os dados do produto do corpo da requisição
            $data = json_decode($request->getRawBody(), true); // Receber os dados JSON

            // Criar um novo objeto Produto e atribuir os dados recebidos
            $produto = new Produto();
            $produto->cliente_id = $data['cliente_id'];
            $produto->attributes = $data;

            // Salvar o produto no banco de dados
            if ($produto->save()) {
                $transaction->commit(); // Confirmar transação
                // Produto cadastrado com sucesso
                Yii::$app->response->statusCode = 201; // Created
                return json_encode(['success' => true, 'message' => 'Produto cadastrado com sucesso.']);
            } else {
                // Erro ao salvar o produto
                Yii::$app->response->statusCode = 400; // Bad Request
                return json_encode(['success' => false, 'message' => 'Erro ao cadastrar produto. Verifique os dados enviados.']);
            }
        } catch (\Throwable $th) {
            $transaction->rollBack(); // Reverter transação em caso de erro
            Yii::$app->response->statusCode = 400; // Bad Request
            return json_encode(['success' => false, 'message' => $th->getMessage()]);
        }
        
    }

    public function actionListarProdutos()
    {
        try {
            $request = Yii::$app->request;
            if (!AuthService::validarToken($request)) {
                // Requisição não é do tipo POST
                Yii::$app->response->statusCode = 405; // Method Not Allowed
                return json_encode(['success' => false, 'message' => 'Token Invalido.']);
            }
            $produtos = Produto::find()->all();

            return $this->asJson($produtos);
        } catch (\Throwable $th) {
            Yii::$app->response->statusCode = 400; // Bad Request
            return json_encode(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function actionListarProdutosClientes()
    {
        try {
            $request = Yii::$app->request;
            if (!AuthService::validarToken($request)) {
                // Requisição não é do tipo POST
                Yii::$app->response->statusCode = 405; // Method Not Allowed
                return json_encode(['success' => false, 'message' => 'Token Invalido.']);
            }
            $produtos = Produto::find()->with('cliente')->all();
            // Converter os dados para JSON
            $jsonProdutos = [];
            foreach ($produtos as $produto) {
                $cliente = $produto->cliente;
                $jsonProdutos[] = [
                    'id' => $produto->id,
                    'nome' => $produto->nome,
                    'preco' => $produto->preco,
                    'foto' => $cliente->foto,
                    'cliente' => [
                        'id' => $cliente->id,
                        'nome' => $cliente->nome,
                        'cpf' => $cliente->cpf,
                        'foto' => $cliente->foto,
                        'sexo' => $cliente->sexo
                    ]
                ];
            }

            return Json::encode($jsonProdutos);
        } catch (\Throwable $th) {
            Yii::$app->response->statusCode = 400; // Bad Request
            return json_encode(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}
