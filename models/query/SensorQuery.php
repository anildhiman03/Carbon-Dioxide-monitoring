<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Sensor]].
 *
 * @see \app\models\Sensor
 */
class SensorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[sensor_status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\Sensor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Sensor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
