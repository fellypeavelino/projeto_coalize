<?php
namespace app\services;

use Firebase\JWT\JWT;
use app\models\User;
use Firebase\JWT\Key;
date_default_timezone_set('America/Sao_Paulo');

class AuthService
{
    public static function generateToken($sub)
    {
        // Obter a data e hora atual
        $dataHoraAtual = new \DateTime();
        // Adicionar uma hora
        $dataHoraAtual->add(new \DateInterval('PT1H')); // PT1H representa 1 hora
        // Formatar a data e hora no padrão brasileiro
        $expirationTime = $dataHoraAtual->format('Y-m-d H:i:s');
        $tokenPayload = [
            'exp' => $expirationTime,
            'sub' => $sub,
        ];
        $secretKey = 'coalize';  // Substitua pelo seu segredo real
        $jwtOptions = [];  // Opções de configuração do JWT, se necessário
        return JWT::encode($tokenPayload, $secretKey, 'HS256', null, $jwtOptions);
    }

    public static function validateDataToken($token)
    {
        if (empty($token)) {
            return false;
        }
        $secretKey = 'coalize';
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        // Obter a data atual
        $dataAtual = new \DateTime();
        // String de data para comparação
        $stringData = $decoded->exp;
        // Converter a string de data para um objeto DateTime
        $dataComparacao = \DateTime::createFromFormat('Y-m-d H:i:s', $stringData);
        return ($dataAtual < $dataComparacao);
    }  
    
    public static function validarToken($request){
        $headers = $request->headers;
        $authorizationHeader = $headers->get('Authorization');
        $authorizationHeader = str_replace("Bearer ", "", $authorizationHeader);
        $authorizationHeader = trim($authorizationHeader);
        $result = AuthService::validateDataToken($authorizationHeader);
        return $result;
    }    
}
