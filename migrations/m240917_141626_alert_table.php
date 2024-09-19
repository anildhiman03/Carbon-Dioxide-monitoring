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
        $this->createTable('alert', [
            'id' => $this->primaryKey(),
            'sensor_id' => $this->integer()->notNull(),
            'start_time' => $this->dateTime()->notNull(),
            'end_time' => $this->dateTime()->notNull(),
            'measurement1' => $this->integer()->notNull(),
            'measurement2' => $this->integer()->notNull(),
            'measurement3' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_alert_sensor', 'alert', 'sensor_id', 'sensor', 'id', 'CASCADE');
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
