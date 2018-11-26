<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\components\QueueTasks\MonitorUrl;
use common\models\UserAgent;
use common\models\Url;

class MonitorController extends Controller
{
    /**
     * Добавляем ссылки на мониторинг в очередь.
     * Контроллер запускаем каждую минуту через cron.
     */
    public function actionIndex()
    {
        $urlList = Url::find()->where(['Active' => 1])->all();

        foreach ($urlList as $url)
        {
            # Получаем user agent по id
            $userAgent = new UserAgent();
            $userAgentName = $userAgent->getUserAgentByID($url->user_agent_id);

            if (self::isUrlMonitor($url))
            {
                # Добавляем ссылку на мониторинг в очередь
                Yii::$app->queue->push(new MonitorUrl([
                    'url' => $url->url,
                    'userAgent' => $userAgentName,
                    'requestType' => strtoupper($url->request_type),
                    'userID' => $url->user_id,
                    'expectedResponse' => $url->expected_response
                ]));

                # Обновляем поле last_check в таблице url
                $url->last_check = time();
                $url->save();
            }
        }
    }

    /**
     * Проверяем нужно ли мониторить ссылку по времени последнего мониторинга и интервалу мониторинга.
     *
     * @param Url $url
     * @return bool
     */
    public static function isUrlMonitor(Url $url)
    {
        $now = intval(date('i'));
        $last_check = intval(date('i', $url->last_check));
        $check_interval = $url->check_interval;

        $timeSinceLastCheck = $now - $last_check;

        if ($last_check == 0)
        {
            $result = true;
        }
        else if ($check_interval <= $timeSinceLastCheck)
        {
            $result = true;
        }
        else
        {
            $result = false;
        }
        return $result;
    }
}