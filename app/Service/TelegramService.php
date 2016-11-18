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
        //define("PRODUCTTEAM", -164742782);
        define("PRODUCTTEAM", -162201704);
        $chat = $this->telegram->getChatByTitle(constant("PRODUCTTEAM"));
        $chat_id = $chat->first()->id;

        $send = $this->botSendMessage($token, $chat_id, $message, $queue);
        return $send;
    }

    public function productDevelopmentBotSendToUser($erp_id, $message, $queue = false)
    {
        //$bot = $this->telegram->getBotByName('productDevelopmentBot');
        $bot = $this->telegram->getBotByName('testBot');
        $token = $bot->first()->token;
        $send = $this->sendMessageToUser($token, $erp_id, $message, $queue);
        return $send;
    }

    public function sendMessageToUser($token, $erp_id, $message, $queue = false)
    {
        $user_id = $this->server->getuserByerpID($erp_id)->telegramID;
        $send = $this->botSendMessage($token, $user_id, $message);
        return $send;
    }

    public function botSendMessage($token, $chat_id, $message, $queue = false)
    {
        if (isset($chat_id)) {
            if ($queue) {
                $this->dispatch(new SendTelegram($token, $chat_id, $message));
                return true;
            }

            $url = 'https://api.telegram.org/bot' . $token . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $file_get = curl_exec($ch);
            curl_close($ch);
            //$file_get = file_get_contents($url);
            $send = json_decode($file_get);
            if ($send->ok) {
                return true;
            }
        }
        return false;
    }


}