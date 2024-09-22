<?php

use yii\db\Migration;

/**
 * Class m240917_141626_alert_table
 */
class m240917_141626_alert_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('alert', [
            'alert_uuid' => $this->char(36)->notNull(),
            'measurement_uuid' => $this->char(36)->notNull(),
            'alert_created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'alert', 'alert_uuid');
        $this->addForeignKey('fk_alert_measurement', 'alert', 'measurement_uuid', 'measurement', 'measurement_uuid', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_alert_sensor', 'alert');
        $this->dropTable('alert');
    }

}
