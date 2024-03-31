<?php

use yii\db\Migration;

/**
 * Class m240330_014100_m210325_120000_create_cliente_table
 */
class m240330_014100_m210325_120000_create_cliente_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cliente}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull()->defaultValue(''),
            'cpf' => $this->string(14)->notNull()->unique()->defaultValue(''),
            'foto' => $this->string(),
            'sexo' => $this->string(1)->notNull()->defaultValue(''),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cliente}}');
    }
}
