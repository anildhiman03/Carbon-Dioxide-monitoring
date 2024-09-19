<?php

namespace app\models;

use app\models\query\MeasurementQuery;
use Yii;

/**
 * This is the model class for table "{{%measurement}}".
 *
 * @property int $id
 * @property int $sensor_id
 * @property int $co2
 * @property string $time
 *
 * @property Sensor $sensor
 */
class Measurement extends \yii\db\ActiveRecord
{
    /**
     * status alert
     */
    const STATUS_ALERT  =   'ALERT';
    /**
     * status warn
     */
    const STATUS_WARM  =   'WARN';
    /**
     * status ok
     */
    const STATUS_OK  =   'OK';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%measurement}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sensor_id', 'co2', 'time'], 'required'],
            [['sensor_id', 'co2'], 'integer'],
            [['time'], 'safe'],
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
            'co2' => 'Co2',
            'time' => 'Time',
        ];
    }

    /**
     * Gets query for [[Sensor]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\SensorQuery
     */
    public function getSensor()
    {
        return $this->hasOne(Sensor::class, ['id' => 'sensor_id']);
    }


    /**
     * @throws \Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $lastMeasurements = self::find()
            ->filterByID($this->sensor_id)
            ->sortByTimeDesc()
            ->limit(3)->all();


        $highMeasurements = array_filter($lastMeasurements, function ($measurement) {
            return $measurement->co2 >= 2000;
        });
        $lowMeasurements = array_filter($lastMeasurements, function ($measurement) {
            return $measurement->co2 < 2000;
        });

        $status = self::STATUS_WARM;

        if (count($highMeasurements) >= 3) {
            $status = self::STATUS_ALERT;
            Alert::createAlert($this->sensor_id, $lastMeasurements);
        } else if (count($lowMeasurements) === 3) {
            $status = self::STATUS_OK;
        }

        Sensor::updateStatus($status, $this->sensor_id);
    }

    /**
     * @throws \Exception
     */
    public static function createMeasurement(string $sensorID , array $data): Measurement
    {
        $measurement = new self([
            'sensor_id' => $sensorID,
            'co2' => $data["co2"],
            'time' => date('Y-m-d H:i:s',strtotime($data["time"]))
        ]);

        if (!$measurement->save()) {
            throw new \Exception($measurement->getErrors());
        }

        return $measurement;

    }
    /**
     * {@inheritdoc}
     * @return MeasurementQuery the active query used by this AR class.
     */
    public static function find(): query\MeasurementQuery
    {
        return new MeasurementQuery(get_called_class());
    }
}
