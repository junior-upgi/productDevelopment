<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Repositories\mobileMessagingSystem\MobileRepositories;

class SendNotify implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $mobile;
    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        array $message
    ) {
        //
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MobileRepositories $mobile)
    {
        //
        $this->mobile = $mobile;
        $m = $this->message;
        $title = $m['title'];
        $content = $m['content'];
        $messageID = $m['messageID'];
        $systemID = $m['systemID'];
        $uid = $m['uid'];
        $recipientID = $m['recipientID'];
        $url = $m['url'];
        $audioFile = $m['audioFile'];
        $projectID = $m['projectID'];
        $productID = $m['productID'];
        $processID = $m['processID'];
        $mobile->insertNotify($title, $content, $messageID, $systemID, $uid, $recipientID, $url, $audioFile, $projectID, $productID, $processID);
    }
}
