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

    public function filterByID($id)
    {
        return $this->andwhere(['sensor_id' => $id]);
    }

    public function sortByTimeDesc()
    {
        return $this->orderBy(['time' => SORT_DESC]);
    }
}
