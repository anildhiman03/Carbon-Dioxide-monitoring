<?php

namespace app\controllers;

use app\models\Measurement;
use app\models\Sensor;
use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class SensorController extends Controller
{

    /**
     * @throws \Exception
     */
    public function actionStoreMeasurement($uuid): array
    {
        try {
            $sensor = Sensor::findOrCreate($uuid);

            $data = json_decode(Yii::$app->getRequest()->getRawBody(),true);

            $measurement = Measurement::createMeasurement($sensor->id, $data);

            return [
                "co2" => $measurement->co2,
                "time" => $measurement->time
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionStatus($uuid): array
    {
        $sensor = Sensor::findOne(['uuid' => $uuid]);

        if (!$sensor) {
            throw new NotFoundHttpException("Sensor not found");
        }

        return [
            "status" => $sensor->status
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionMetrics($uuid): array
    {
        $sensor = Sensor::findOne(['uuid' => $uuid]);

        if (!$sensor) {
            throw new NotFoundHttpException("Sensor not found");
        }

        $last30Days = (new \DateTime())->modify('-30 days')->format('Y-m-d H:i:s');
        $measurements = Measurement::find()->where(['sensor_id' => $sensor->id])->andWhere(['>=', 'time', $last30Days])->all();

        $max = max(array_column($measurements, 'co2'));
        $avg = array_sum(array_column($measurements, 'co2')) / count($measurements);

        return [
            'maxLast30Days' => $max,
            'avgLast30Days' => $avg
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionAlerts($uuid): array
    {
        $sensor = Sensor::findOne(['uuid' => $uuid]);

        if (!$sensor) {
            throw new NotFoundHttpException("Sensor not found");
        }

        return $sensor->getAlerts()->asArray()->all();
    }
}
