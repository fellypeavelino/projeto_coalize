<?php

namespace app\models;

use yii\db\ActiveRecord;

class Cliente extends ActiveRecord
{

    public static function tableName()
    {
        return 'cliente'; // Nome da tabela no banco de dados
    }

    public function rules()
    {
        return [
            [['nome', 'cpf', 'foto', 'sexo'], 'required'],
            [['nome', 'cpf', 'foto', 'sexo'], 'string', 'max' => 255],
            // Adicione outras regras de validação conforme necessário
        ];
    }

    // Relacionamento com a tabela "endereco"
    public function getEndereco()
    {
        return $this->hasOne(Endereco::class, ['cliente_id' => 'id']);
    }

    public function getEnderecos()
    {
        return $this->hasMany(Endereco::class, ['cliente_id' => 'id']);
    }
}
