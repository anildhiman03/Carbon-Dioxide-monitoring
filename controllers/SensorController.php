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

            $measurement = Measurement::createMeasurement($sensor->sensor_uuid, $data);

            return [
                "co2" => $measurement->measurement_co2,
                "time" => $measurement->measurement_created_at
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
        $sensor = Sensor::findOne(['sensor_uuid' => $uuid]);

        if (!$sensor) {
            throw new NotFoundHttpException("Sensor not found");
        }

        return [
            "status" => $sensor->sensor_status
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionMetrics($uuid): array
    {
        return Sensor::getMetricData($uuid);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionAlerts($uuid): array
    {
        $allAlerts = Sensor::getAllSensorAlerts($uuid);

        $alerts = [];
        foreach ($allAlerts as $key => $value) {
            $alerts['measurement' . ++$key] = $value['measurement']['measurement_co2'];
        }

        return array_merge(
            [
                'startTime' => current($allAlerts)['alert_created_at'],
                'endTime' => end($allAlerts)['alert_created_at'],
            ],
            $alerts
        );

    }
}
