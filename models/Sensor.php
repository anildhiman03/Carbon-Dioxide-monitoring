<?php

namespace app\models;

use app\models\query\SensorQuery;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%sensor}}".
 *
 * @property int $sensor_uuid
 * @property string $sensor_status
 *
 * @property Alert[] $alerts
 * @property Measurement[] $measurements
 */
class Sensor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return '{{%sensor}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['sensor_uuid'], 'required'],
            [['sensor_uuid'], 'unique'],
            [['sensor_uuid', 'sensor_status'], 'string', 'max' => 255],
            [['sensor_created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'sensor_uuid' => 'UUID',
            'sensor_status' => 'Status',
            'sensor_created_at' => 'Created at',
        ];
    }

    /**
     * Gets query for [[Alerts]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\AlertQuery
     */
    public function getAlerts()
    {
        return $this->hasMany(Alert::class, ['measurement_uuid' => 'measurement_uuid'])
            ->viaTable('measurement', ['sensor_uuid' => 'sensor_uuid']);
    }

    /**
     * Gets query for [[Measurements]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeasurements(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Measurement::class, ['sensor_uuid' => 'sensor_uuid']);
    }

    /**
     * @param $sensor_status
     * @param $sensor_uuid
     * @return void
     */
    public static function updateStatus($sensor_status, $sensor_uuid)
    {
        self::updateAll(['sensor_status' => $sensor_status], ['sensor_uuid' => $sensor_uuid]);
    }

    /**
     * @throws \Exception
     */
    public static function createSensor($uuid): Sensor
    {
        $sensor = new self();
        $sensor->sensor_uuid = $uuid;

        if (!$sensor->save()) {
            throw new \Exception(json_encode($sensor->getErrors()));
        }

        return $sensor;
    }

    /**
     * @throws \Exception
     */
    public static function findOrCreate($uuid): Sensor
    {
        $sensor = self::findOne(['sensor_uuid' => $uuid]);

        if (!$sensor) {
            $sensor = self::createSensor($uuid);
        }

        return $sensor;
    }

    /**
     * @throws NotFoundHttpException
     */
    public static function getAllSensorAlerts($uuid): array
    {
        $sensor = self::loadSensorData($uuid);

        if (!$sensor->getAlerts()->count()) {
            throw new NotFoundHttpException("No alert Found");
        }

        return $sensor->getAlerts()->with('measurement')->orderBy('alert_created_at')->asArray()->all();
    }

    /**
     * @throws NotFoundHttpException
     */
    public static function getMetricData($uuid): array
    {
        $sensor = self::loadSensorData($uuid);

        $last30Days = (new \DateTime())->modify('-30 days')->format('Y-m-d H:i:s');
        $measurements = Measurement::find()
            ->filterByID($sensor->sensor_uuid)
            ->greaterThenTime($last30Days)
            ->all();

        if (count($measurements) === 0 ) {
            return [
                'maxLast30Days' => 0,
                'avgLast30Days' => 0
            ];
        }

        $max = max(array_column($measurements, 'measurement_co2'));
        $avg = array_sum(array_column($measurements, 'measurement_co2')) / count($measurements);

        return [
            'maxLast30Days' => $max,
            'avgLast30Days' => $avg
        ];
    }

    public function beforeSave($insert): bool
    {
        if($insert === self::EVENT_BEFORE_INSERT && !$this->sensor_uuid){
            $this->sensor_uuid = new yii\db\Expression('UUID()');
        }
        return parent::beforeSave($insert);
    }


    /**
     * @throws NotFoundHttpException
     */
    private static function loadSensorData($uuid): Sensor
    {
        $sensor = self::findOne(['sensor_uuid' => $uuid]);

        if (!$sensor) {
            throw new NotFoundHttpException("Sensor not found");
        }
        return $sensor;
    }

    /**
     * {@inheritdoc}
     * @return SensorQuery the active query used by this AR class.
     */
    public static function find() : SensorQuery
    {
        return new SensorQuery(get_called_class());
    }
}
