<?php

use yii\db\Migration;

/**
 * Class m240330_014623_m210325_130000_create_endereco_table
 */
class m240330_014623_m210325_130000_create_endereco_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%endereco}}', [
            'id' => $this->primaryKey(),
            'cep' => $this->string(8)->notNull()->defaultValue(''),
            'logradouro' => $this->string()->notNull()->defaultValue(''),
            'numero' => $this->string(10),
            'cidade' => $this->string()->notNull()->defaultValue(''),
            'estado' => $this->string(2)->notNull()->defaultValue(''),
            'complemento' => $this->string(),
            'cliente_id' => $this->integer()->notNull(),
        ]);

        // Adicionar a chave estrangeira para o relacionamento com a tabela "cliente"
        $this->addForeignKey(
            'fk-endereco-cliente_id',
            'endereco',
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
        $this->dropForeignKey('fk-endereco-cliente_id', 'endereco');

        $this->dropTable('endereco');
    }
}
