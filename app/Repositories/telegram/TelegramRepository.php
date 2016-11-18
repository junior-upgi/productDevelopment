<?php
namespace App\Repositories\telegram;

use App\Models\telegram\Bot;
use App\Models\telegram\Chat;

class TelegramRepository
{
    public $bot;
    public $chat;

    public function __construct(
        Bot $bot,
        Chat $chat
    ) {
        $this->bot = $bot;
        $this->chat = $chat;
    }

    public function getBotByName($name) 
    {
        $model = $this->bot->where('username', $name);
        return $model;
    }

    public function getChatByTitle($title)
    {
        $model = $this->chat->where('id', $title);
        return $model;
    }
}