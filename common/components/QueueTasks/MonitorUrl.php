<?php

namespace common\components\QueueTasks;

use common\components\TelegramBotAPI;
use Yii;
use yii\base\BaseObject;
use \yii\queue\JobInterface;
use common\models\User;
use yii\base\ErrorException;

date_default_timezone_set('Asia/Novosibirsk');

class MonitorUrl extends BaseObject implements JobInterface
{
    public $url;
    public $userAgent;
    public $requestType;
    public $userID;
    public $expectedResponse;

    public function execute($queue)
    {
        # Отправляем curl запрос, получаем код ответа и ошибки
        $resultCurlRequest = self::curlRequest($this->url, $this->userAgent, $this->requestType);
        $response = $resultCurlRequest['response'];
        $error = $resultCurlRequest['error'];

        # Если есть ошибки или код ответа не ожидаем, то
        # 1. отправляем уведомление в telegram
        # 2. отправляем уведомление на email
        if (!empty($error) || !in_array($response, explode(',', $this->expectedResponse)))
        {
            self::sendTelegram($this->userID, $this->url, $response, $error);
            self::sendMail($this->userID, $this->url, $response, $error);
        }
        # Печатаем результат всё в лог в независимости от результата
        self::writeResultToFile($this->url, $this->userAgent, $this->requestType, $response, $error);
    }

    /**
     * Отправка уведомления на email, в случае если ссылка не доступна или код ответа не ожидаем.
     *
     * @param $userID
     * @param $url
     * @param $response
     * @param $error
     */
    public static function sendMail($userID, $url, $response, $error)
    {
        if (empty($error))
        {
            $body = 'Unexpected response (code ' . $response . ') from ' . $url;
        }
        else
        {
            $body = $error;
        }

        $adminEmail = Yii::$app->params['adminEmail'];
        $user = User::findIdentity($userID);
        Yii::$app->mailer->compose()
            ->setFrom($adminEmail)
            ->setTo($user->email)
            ->setSubject('Monitoring URL')
            ->setHtmlBody('<b>' . $body . '</b>')
            ->send();
    }

    /**
     * Независимо от результата мониторинга пишём всё в MonitorURL.
     *
     * @param $url
     * @param $userAgent
     * @param $requestType
     * @param $response
     * @param $error
     */
    public static function writeResultToFile($url, $userAgent, $requestType, $response, $error)
    {
        $date = date('Y-m-d H:i:s');
        $result = 'Time: ' . $date . ' ' . "\n" .
            'URL: ' . '"' . $url . '"' . ' ' . "\n" .
            'UserAgent: ' . '"' .$userAgent . '"' . ' ' . "\n" .
            'RequestType: ' . '"' .$requestType . '"' . "\n";

        if (empty($error))
        {
            $result = $result . 'Response: ' . $response . "\n" . "\n";
        }
        else
        {
            $result = $result . 'Error: ' . '"' . $error . '"' . "\n" . "\n";
        }
        file_put_contents('MonitorURL.txt', $result, FILE_APPEND);
    }

    /**
     * Отправляем curl запрос и получаем код ответа.
     * Возвращаем массив с кодом ответа и ошибкой.
     * В случае если есть ошибка, то код ответа 0.
     * В случае если нет ошибки, то строка с ошибкой пустая.
     *
     * @param $url
     * @param $userAgent
     * @param $requestType
     * @return array
     */
    public static function curlRequest($url, $userAgent, $requestType)
    {
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_NOBODY         => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_USERAGENT      => $userAgent,
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => $requestType,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $error = curl_error($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['response' => $response, 'error' => $error];
    }

    /**
     * Отправка уведомления в Telegram, в случае если ссылка не доступна или код ответа не ожидаем.
     * В случае если сообщение не доставлено пользователю, то пишем в лог TelegramBotError.
     *
     * @param $userID
     * @param $url
     * @param $response
     * @param $error
     */
    public static function sendTelegram($userID, $url, $response, $error)
    {
        $user = User::findIdentity($userID);

        if (empty($error))
        {
            $message = 'Unexpected response (code ' . $response . ') from ' . $url;
        }
        else
        {
            $message = $error;
        }

        try
        {
            self::sendMessage($user->telegram_id, $message);
        }
        catch (ErrorException $exception)
        {
            if (!is_null($exception))
            {
                $date = date('Y-m-d H:i:s');
                $result = 'Time: ' . $date . ' ' . "\n" .
                    'Username: ' . '"' . $user->username . '"' . ' ' . "\n" .
                    'URL: ' . '"' . $url . '"' . ' ' . "\n" .
                    'Message: ' . '"' . $exception->getMessage() . '"' . "\n" .
                    'Trace: ' . '"' . $exception->getTraceAsString() . '"' . "\n" . "\n";
                file_put_contents('TelegramBotError.txt', $result, FILE_APPEND);
            }
        }
    }

    /**
     * Перехват ошибки, если сообщение не доставлено пользователю.
     *
     * @param integer $chat_id
     * @param string $message
     * @throws ErrorException
     */
    public static function sendMessage($chat_id, $message)
    {
        $token = Yii::$app->params['token'];
        $tg = new TelegramBotAPI($token);
        if ($tg->sendMessage($chat_id, $message) == false)
        {
            throw new ErrorException();
        }
    }

}