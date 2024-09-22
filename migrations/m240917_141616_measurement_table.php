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
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('measurement', [
            'measurement_uuid' => $this->char(36)->notNull(),
            'sensor_uuid' => $this->char(36)->notNull(),
            'measurement_co2' => $this->integer()->notNull(),
            'measurement_created_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'measurement', 'measurement_uuid');
        $this->addForeignKey('fk_measurement_sensor', 'measurement', 'sensor_uuid', 'sensor', 'sensor_uuid', 'CASCADE');
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
