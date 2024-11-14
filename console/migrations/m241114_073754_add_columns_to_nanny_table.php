<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%nanny}}`.
 */
class m241114_073754_add_columns_to_nanny_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->addColumn('nanny', 'name', $this->string());
        $this->addColumn('nanny', 'email', $this->string());
        $this->addColumn('nanny', 'rule_accepted', $this->boolean()->defaultValue(false));
        $this->addColumn('nanny', 'share_vk', $this->boolean()->defaultValue(false));
        $this->addColumn('nanny', 'share_fb', $this->boolean()->defaultValue(false));
        $this->addColumn('nanny', 'winner_status', $this->boolean()->defaultValue(false));
        $this->addColumn('nanny', 'moderation_status', $this->integer()->defaultValue(0));
        $this->addColumn('nanny', 'vk', $this->boolean()->defaultValue(false));
        $this->addColumn('nanny', 'fb', $this->boolean()->defaultValue(false));
        $this->addColumn('nanny', 'share_time', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropColumn('nanny', 'name');
        $this->dropColumn('nanny', 'email');
        $this->dropColumn('nanny', 'rule_accepted');
        $this->dropColumn('nanny', 'share_vk');
        $this->dropColumn('nanny', 'share_fb');
        $this->dropColumn('nanny', 'winner_status');
        $this->dropColumn('nanny', 'moderation_status');
        $this->dropColumn('nanny', 'vk');
        $this->dropColumn('nanny', 'fb');
        $this->dropColumn('nanny', 'share_time');
    }
}
