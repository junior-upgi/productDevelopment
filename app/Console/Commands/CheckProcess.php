<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class CheckProcess extends Command
{
    // 命令名稱
    protected $signature = 'checkProcess';

    // 說明文字
    protected $description = '[檢核]每日檢核程序執行狀態';

    public function __construct()
    {
        parent::__construct();
    }

    // Console 執行的程式
    public function handle()
    {
        //執行排程檢查


        // 檔案紀錄在 storage/test.log
        $log_file_path = storage_path('logs/checkProcess.log');

        // 記錄當時的時間
        $log_info = [
            'title' => 'title',
            'content' => 'content',
            'date' => date('Y-m-d H:i:s')
        ];

        // 記錄 JSON 字串
        $log_info_json = json_encode($log_info) . "\r\n";

        // 記錄 Log
        File::append($log_file_path, $log_info_json);
    }
}