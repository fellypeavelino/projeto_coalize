<?php

// models/Produto.php

namespace app\models;

use yii\db\ActiveRecord;

class Produto extends ActiveRecord
{
    public static function tableName()
    {
        return 'produto'; // Nome da tabela de produtos no banco de dados
    }

    public function rules()
    {
        return [
            [['nome', 'preco', 'foto'], 'required'],
            [['preco'], 'number'],
            [['nome', 'foto'], 'string', 'max' => 255],
        ];
    }

    // Se houver relacionamentos com outras tabelas, defina-os aqui
    // Exemplo de relação com a tabela Cliente:
    public function getCliente()
    {
        return $this->hasOne(Cliente::class, ['id' => 'cliente_id']);
    }
}
