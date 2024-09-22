<?php

use yii\db\Migration;

/**
 * Class m240917_141606_sensor_table
 */
class m240917_141606_sensor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('sensor', [
            'sensor_uuid' => $this->char(36)->notNull(),
            'sensor_status' => $this->string()->notNull()->defaultValue('OK'),
            'sensor_created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'sensor', 'sensor_uuid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('sensor');
    }
}
