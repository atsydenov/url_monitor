<?php

namespace common\components;

use yii\base\BaseObject;

class TelegramBotAPI extends BaseObject
{
    const BASE_URL = 'https://api.telegram.org';
    const BOT_URL = '/bot';

    public $token;
    public $baseURL;
    public $baseFileURL;

    public function __construct($token)
    {
        $this->token = $token;
        $this->baseURL = self::BASE_URL . self::BOT_URL . $this->token . '/';
    }

    /**
     * Send text messages.
     *
     * @link https://core.telegram.org/bots/api#sendmessage
     *
     * @param int            $chat_id
     * @param string         $text
     * @param string         $parse_mode
     * @param bool           $disable_web_page_preview
     * @param int            $reply_to_message_id
     * @param KeyboardMarkup $reply_markup
     *
     * @return Array
     */
    public function sendMessage($chat_id, $text, $parse_mode = null, $disable_web_page_preview = false, $reply_to_message_id = null, $reply_markup = null)
    {
        $params = compact('chat_id', 'text', 'parse_mode', 'disable_web_page_preview', 'reply_to_message_id', 'reply_markup');
        return $this->sendRequest('sendMessage', $params);
    }

    private function sendRequest($method, $params)
    {
        return json_decode(file_get_contents($this->baseURL . $method . '?' . http_build_query($params)), true);
    }

}