<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%produto}}`.
 */
class m240330_164602_create_produto_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('produto', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'preco' => $this->decimal(10, 2)->notNull(),
            'foto' => $this->string()->defaultValue(null),
            'cliente_id' => $this->integer()->notNull(),
        ]);

        // Adicionar chave estrangeira para a tabela cliente
        $this->addForeignKey(
            'fk-produto-cliente_id',
            'produto',
            'cliente_id',
            'cliente',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Remover chave estrangeira
        $this->dropForeignKey('fk-produto-cliente_id', 'produto');

        $this->dropTable('produto');
    }
}
