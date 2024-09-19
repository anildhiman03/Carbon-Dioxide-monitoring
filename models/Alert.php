<?php

namespace app\models;

use app\models\query\AlertQuery;
use Yii;
use yii\db\Exception;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%alert}}".
 *
 * @property int $id
 * @property int $sensor_id
 * @property string $start_time
 * @property string $end_time
 * @property int $measurement1
 * @property int $measurement2
 * @property int $measurement3
 *
 * @property Sensor $sensor
 */
class Alert extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%alert}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sensor_id', 'start_time', 'end_time', 'measurement1', 'measurement2', 'measurement3'], 'required'],
            [['sensor_id', 'measurement1', 'measurement2', 'measurement3'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['sensor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sensor::class, 'targetAttribute' => ['sensor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'sensor_id' => 'Sensor ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'measurement1' => 'Measurement1',
            'measurement2' => 'Measurement2',
            'measurement3' => 'Measurement3',
        ];
    }

    /**
     * Gets query for [[Sensor]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getSensor(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Sensor::class, ['id' => 'sensor_id']);
    }


    /**
     * @param $sensorID
     * @param $measurements
     * @return void
     * @throws \Exception
     */
    public static function createAlert($sensorID, $measurements)
    {
        $alert = new self([
            'sensor_id' => $sensorID,
            'start_time' => $measurements[0]->time,
            'end_time' => $measurements[2]->time,
            'measurement1' => $measurements[0]->co2,
            'measurement2' => $measurements[1]->co2,
            'measurement3' => $measurements[2]->co2,
        ]);

        if (!$alert->save()) {
            throw new \Exception($alert->getErrors());
        }
        Yii::info('New alert has been created with id ' . $alert->id );
    }

    /**
     * {@inheritdoc}
     * @return AlertQuery the active query used by this AR class.
     */
    public static function find(): AlertQuery
    {
        return new AlertQuery(get_called_class());
    }
}
