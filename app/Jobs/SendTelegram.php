<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Repositories\mobileMessagingSystem\MobileRepositories;

class SendTelegram implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $token;
    public $chat_id;
    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $token, string $chat_id, string $message) 
    {
        //
        $this->token = $token;
        $this->chat_id = $chat_id;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $url = 'https://api.telegram.org/bot' . $this->token . '/sendMessage?chat_id=' . $this->chat_id . '&text=' . urlencode($this->message);
        //$encodeUrl = urlencode($url);
        $file_get = file_get_contents($url);
        $send = json_decode($file_get);
    }
}
