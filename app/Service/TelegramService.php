<?php
namespace App\Service;

use Illuminate\Foundation\Bus\DispatchesJobs;

use App\Jobs\SendTelegram;
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use App\Repositories\telegram\TelegramRepository;
use App\Repositories\upgiSystem\UpgiSystemRepository;

class TelegramService
{
    use DispatchesJobs;

    public $common;
    public $server;
    public $telegram;
    public $upgi;

    public function __construct(
        Common $common,
        ServerData $server,
        TelegramRepository $telegram,
        UpgiSystemRepository $upgi
    ) {
        $this->common = $common;
        $this->server = $server;
        $this->telegram = $telegram;
        $this->upgi = $upgi;
    }

    public function sendProductTeam($message, $queue = false)
    {
        //$bot = $this->telegram->getBotByName('productDevelopmentBot');
        $bot = $this->telegram->getBotByName('testBot');
        $token = $bot->first()->token;
        define("PRODUCTTEAM", -162201704);
        $chat = $this->telegram->getChatByTitle(constant("PRODUCTTEAM"));
        $chat_id = $chat->first()->id;
        if ($queue) {
            $this->dispatch(new SendTelegram($token, $chat_id, $message));
            return true;
        }
        $send = $this->botSendMessage($token, $chat_id, $message);
        return $send;
    }

    public function botSendMessage($token, $chat_id, $message)
    {
        $url = 'https://api.telegram.org/bot' . $token . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message);
        //$encodeUrl = urlencode($url);
        $file_get = file_get_contents($url);
        $send = json_decode($file_get);
        if ($send->ok) {
            return true;
        }
        return false;
    }


}