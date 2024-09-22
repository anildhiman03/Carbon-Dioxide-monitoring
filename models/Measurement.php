<?php

namespace app\models;

use app\models\query\MeasurementQuery;
use Yii;

/**
 * This is the model class for table "{{%measurement}}".
 *
 * @property int $measurement_uuid
 * @property int $sensor_uuid
 * @property int $measurement_co2
 * @property string $measurement_created_at
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
            [['sensor_uuid', 'measurement_co2'], 'required'],
            [['measurement_uuid','sensor_uuid'], 'string'],
            [['measurement_co2'], 'integer'],
            [['measurement_created_at'], 'safe'],
            [['sensor_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Sensor::class, 'targetAttribute' => ['sensor_uuid' => 'sensor_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'measurement_uuid' => 'UUID',
            'sensor_uuid' => 'Sensor UUID',
            'measurement_co2' => 'Co2',
            'measurement_created_at' => 'Created At',
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert)
            $this->measurement_uuid = (new \yii\db\Query)->select(new yii\db\Expression('UUID()'))->scalar();

        return parent::beforeSave($insert);
    }

    /**
     * Gets query for [[Sensor]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\SensorQuery
     */
    public function getSensor()
    {
        return $this->hasOne(Sensor::class, ['sensor_uuid' => 'sensor_uuid']);
    }


    /**
     * @throws \Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $lastMeasurements = self::find()
            ->filterByID($this->sensor_uuid)
            ->sortByTimeDesc()
            ->limit(3)->all();


        $highMeasurements = array_filter($lastMeasurements, function ($measurement) {
            return $measurement->measurement_co2 >= 2000;
        });
        $lowMeasurements = array_filter($lastMeasurements, function ($measurement) {
            return $measurement->measurement_co2 < 2000;
        });

        $status = self::STATUS_WARM;

        if (count($highMeasurements) >= 3) {
            $status = self::STATUS_ALERT;
            Alert::createAlert($this->measurement_uuid);
        } else if (count($lowMeasurements) === 3) {
            $status = self::STATUS_OK;
        }

        Sensor::updateStatus($status, $this->sensor_uuid);
    }

    /**
     * @throws \Exception
     */
    public static function createMeasurement(string $sensor_uuid , array $data): Measurement
    {
        $measurement = new self([
            'sensor_uuid' => $sensor_uuid,
            'measurement_co2' => $data["co2"],
            'measurement_created_at' => date('Y-m-d H:i:s')
        ]);

        if (!$measurement->save()) {
            throw new \Exception(json_encode($measurement->getErrors()));
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
