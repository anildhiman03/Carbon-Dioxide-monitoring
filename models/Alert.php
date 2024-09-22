<?php

namespace app\models;

use app\models\query\AlertQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%alert}}".
 *
 * @property int $alert_uuid
 * @property int $measurement_uuid
 * @property string $alert_created_at
 *
 * @property Measurement $measurement
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
            [['alert_uuid','measurement_uuid'], 'string'],
            [['alert_created_at','measurement_uuid'], 'safe'],
            [['measurement_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Measurement::class, 'targetAttribute' => ['measurement_uuid' => 'measurement_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'alert_uuid' => 'UUID',
            'measurement_uuid' => 'measurement UUID',
            'alert_created_at' => 'created At',
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert)
            $this->alert_uuid = (new \yii\db\Query)->select(new yii\db\Expression('UUID()'))->scalar();

        return parent::beforeSave($insert);
    }

    /**
     * Gets query for [[Sensor]].
     *
     * @return ActiveQuery
     */
    public function getMeasurement(): ActiveQuery
    {
        return $this->hasOne(Measurement::class, ['measurement_uuid' => 'measurement_uuid']);
    }


    /**
     * @param $measurement_uuid
     * @return void
     * @throws \Exception
     */
    public static function createAlert($measurement_uuid)
    {
        $alert = new self(['measurement_uuid' => $measurement_uuid]);

        if (!$alert->save()) {
            throw new \Exception(json_encode($alert->getErrors()));
        }
        Yii::info('New alert has been created with id ' . $alert->alert_uuid );
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
