<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use App\Service\ProjectCheckService;

class CheckEveryDay extends Command
{
    // 命令名稱
    protected $signature = 'checkEveryDay';

    // 說明文字
    protected $description = '[檢核]每日檢核';

    public $check;

    public function __construct(ProjectCheckService $check)
    {
        parent::__construct();
        $this->check = $check;
    }

    // Console 執行的程式
    public function handle()
    {
        // 檔案紀錄在 storage/logs/check.log
        $log_file_path = storage_path('logs/check.log');

        //執行排程檢查
        $process = $this->check->everyDay();
        $success = 0;
        $fail = 0;
        $total = count($process);
        foreach ($process as $list) {
            $list['success'] ? $success++ : $fail++;
        }

        //寫入log
        $log_info = 'checkEveryDay# ' .  date('Y-m-d H:i:s') . ",total=$total,success=$success,fail=$fail" . "\r\n";
        File::append($log_file_path, $log_info);
    }
}