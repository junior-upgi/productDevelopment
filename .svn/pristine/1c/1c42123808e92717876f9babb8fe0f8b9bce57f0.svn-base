<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use App\Service\ProjectCheckService;

class CheckProcess extends Command
{
    // 命令名稱
    protected $signature = 'checkProcess';

    // 說明文字
    protected $description = '[檢核]每日檢核程序執行狀態';

    public $check;

    public function __construct(ProjetCheckService $check)
    {
        parent::__construct();
        $this->check = $check;
    }

    // Console 執行的程式
    public function handle()
    {
        //執行排程檢查
        $process = $check->delayProcess();

        // 檔案紀錄在 storage/test.log
        $log_file_path = storage_path('logs/checkProcess.log');

        // 記錄 JSON 字串
        $log_info_json = json_encode($process) . "\r\n";

        // 記錄 Log
        File::append($log_file_path, $log_info_json);
    }
}