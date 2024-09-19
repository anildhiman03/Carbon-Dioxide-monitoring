<?php

namespace app\models\query;

use app\models\Alert;

/**
 * This is the ActiveQuery class for [[Alert]].
 *
 * @see Alert
 */
class AlertQuery extends \yii\db\ActiveQuery
{

    /**
     * {@inheritdoc}
     * @return Alert[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Alert|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
