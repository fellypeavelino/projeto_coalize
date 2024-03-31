<?php

namespace app\models;

use yii\db\ActiveRecord;

class Endereco extends ActiveRecord
{

    public static function tableName()
    {
        return 'endereco'; // Nome da tabela no banco de dados
    }

    public function rules()
    {
        return [
            [['cep', 'logradouro', 'numero', 'cidade', 'estado'], 'required'],
            [['cep'], 'string', 'max' => 8],
            [['logradouro', 'numero', 'cidade', 'estado', 'complemento'], 'string', 'max' => 255],
            // Adicione outras regras de validação conforme necessário
        ];
    }

    // Relacionamento com a tabela "cliente"
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
    }
}
