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
        $this->createTable('sensor', [
            'id' => $this->primaryKey(),
            'uuid' => $this->string()->notNull(),
            'status' => $this->string()->notNull()->defaultValue('OK')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('sensor');
    }
}
