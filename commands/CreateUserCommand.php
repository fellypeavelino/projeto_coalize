<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;
use Firebase\JWT\JWT;
use app\services\AuthService;

class CreateUserCommand extends Controller
{
    public function actionIndex($login, $senha, $nome)
    {
        $user = User::findOne(['login' => $login]);

        if ($user) {
            // Se o usuário já existir, atualize seus dados
            $user->senha = md5($senha);
            $user->nome = $nome;
            $user->save();
        } else {
            // Se o usuário não existir, crie um novo
            $user = new User();
            $user->login = $login;
            $user->senha =  md5($senha);
            $user->nome = $nome;
            $user->save();
        }

        $token = AuthService::generateToken($login.";".md5($senha));

        echo "Usuário criado/atualizado com sucesso.\n";
        echo "Token: $token\n";
    }

}
