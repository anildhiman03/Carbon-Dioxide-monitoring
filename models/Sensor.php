<?php

namespace app\models;

use app\models\query\SensorQuery;
use Yii;

/**
 * This is the model class for table "{{%sensor}}".
 *
 * @property int $id
 * @property string $uuid
 * @property string $status
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
            [['uuid'], 'required'],
            [['uuid', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Alerts]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\AlertQuery
     */
    public function getAlerts()
    {
        return $this->hasMany(Alert::class, ['sensor_id' => 'id']);
    }

    /**
     * Gets query for [[Measurements]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeasurements(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Measurement::class, ['sensor_id' => 'id']);
    }

    /**
     * @param $status
     * @param $id
     * @return void
     */
    public static function updateStatus($status, $id)
    {
        self::updateAll(['status' => $status], ['id' => $id]);
    }

    /**
     * @throws \Exception
     */
    public static function createSensor($uuid): Sensor
    {
        $sensor = new self();
        $sensor->uuid = $uuid;

        if (!$sensor->save()) {
            throw new \Exception($sensor->getErrors());
        }

        return $sensor;
    }

    /**
     * @throws \Exception
     */
    public static function findOrCreate($uuid): Sensor
    {
        $sensor = self::findOne(['uuid' => $uuid]);

        if (!$sensor) {
            $sensor = self::createSensor($uuid);
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
