<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%nanny}}`.
 */
class m241113_142834_create_nanny_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    final public function safeUp(): void
    {
        $this->createTable('{{%nanny}}', [
            'id' => $this->primaryKey(),
            'child_name' => $this->string(),
            'child_profession' => $this->string(),
            'picture' => $this->string(),
            'image_vk' => $this->string(),
            'image_fb' => $this->string(),
            'token' => $this->string(),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата изменения'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    final public function safeDown(): void
    {
        $this->dropTable('{{%nanny}}');
    }
}
