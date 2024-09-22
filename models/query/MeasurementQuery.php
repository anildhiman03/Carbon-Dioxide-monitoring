<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Measurement]].
 *
 * @see \app\models\Measurement
 */
class MeasurementQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return \app\models\Measurement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Measurement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $uuid
     * @return MeasurementQuery
     */
    public function filterByID($uuid): MeasurementQuery
    {
        return $this->andwhere(['sensor_uuid' => $uuid]);
    }

    /**
     * @return MeasurementQuery
     */
    public function sortByTimeDesc(): MeasurementQuery
    {
        return $this->orderBy(['measurement_created_at' => SORT_DESC]);
    }
    /**
     * @return MeasurementQuery
     */
    public function greaterThenTime($time): MeasurementQuery
    {
        return $this->andWhere(['>=', 'measurement_created_at', $time]);
    }
}
