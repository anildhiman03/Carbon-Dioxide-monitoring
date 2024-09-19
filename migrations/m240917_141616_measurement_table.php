<?php

use yii\db\Migration;

/**
 * Class m240917_141616_measurement_table
 */
class m240917_141616_measurement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('measurement', [
            'id' => $this->primaryKey(),
            'co2' => $this->integer()->notNull(),
            'sensor_id' => $this->integer()->notNull(),
            'time' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk_measurement_sensor', 'measurement', 'sensor_id', 'sensor', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_measurement_sensor', 'measurement');
        $this->dropTable('measurement');
    }
}
