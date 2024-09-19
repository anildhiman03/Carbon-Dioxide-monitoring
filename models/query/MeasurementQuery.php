<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Measurement]].
 *
 * @see \app\models\Measurement
 */
class MeasurementQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
     * @param $id
     * @return MeasurementQuery
     */
    public function filterByID($id): MeasurementQuery
    {
        return $this->andwhere(['sensor_id' => $id]);
    }

    /**
     * @return MeasurementQuery
     */
    public function sortByTimeDesc(): MeasurementQuery
    {
        return $this->orderBy(['time' => SORT_DESC]);
    }
    /**
     * @return MeasurementQuery
     */
    public function greaterThenTime($time): MeasurementQuery
    {
        return $this->andWhere(['>=', 'time', $time]);
    }
}
