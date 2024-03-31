<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Produto;

class ProdutoController extends Controller
{
    public function actionCadastrar()
    {
        $request = Yii::$app->request;
        return json_encode(['success' => true, 'message' => 'Produto cadastrado com sucesso.']);
        // // Verificar se é uma requisição POST
        // if ($request->isPost) {
        //     // Receber os dados do produto do corpo da requisição
        //     $data = json_decode($request->getRawBody(), true); // Receber os dados JSON

        //     // Criar um novo objeto Produto e atribuir os dados recebidos
        //     $produto = new Produto();
        //     $produto->attributes = $data;

        //     // Salvar o produto no banco de dados
        //     if ($produto->save()) {
        //         // Produto cadastrado com sucesso
        //         Yii::$app->response->statusCode = 201; // Created
        //         return json_encode(['success' => true, 'message' => 'Produto cadastrado com sucesso.']);
        //     } else {
        //         // Erro ao salvar o produto
        //         Yii::$app->response->statusCode = 400; // Bad Request
        //         return json_encode(['success' => false, 'message' => 'Erro ao cadastrar produto. Verifique os dados enviados.']);
        //     }
        // } else {
        //     // Requisição não é do tipo POST
        //     Yii::$app->response->statusCode = 405; // Method Not Allowed
        //     return json_encode(['success' => false, 'message' => 'Método não permitido. Utilize POST para cadastrar produtos.']);
        // }
    }
}
