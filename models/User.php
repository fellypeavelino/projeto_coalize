<?php
namespace app\models;

use yii\db\ActiveRecord;
use Firebase\JWT\JWT;

class User extends ActiveRecord
{
    public static function tableName()
    {
        return 'user'; // Nome da tabela de usuários no banco de dados
    }

    public function validateSenha($senha)
    {
        return Yii::$app->security->validatePassword($senha, $this->senha);
    }

    public function generateToken()
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // Expira em 1 hora
        $tokenPayload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $this->login,
        ];
        $secretKey = 'seu-segredo-aqui';
        return JWT::encode($tokenPayload, $secretKey);
    }
}
