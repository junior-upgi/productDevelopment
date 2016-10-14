<?php
/**
 * 檢查專案程序相關內容
 * 1、檢查開始執行且尚完回工之程序，推播剩餘工時
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/14
 * @since 1.0.0 spark: 於此版本開始編寫註解
*/
namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use App\Service\ProjectCheckService;

/**
 * Class CheckProcess
 *
 * @package App\Console\Commands
*/
class CheckProcess extends Command
{
    /** @var string 命令名稱 */
    protected $signature = 'checkProcess';
    /** @var string 命令描述 */
    protected $description = '[檢核]每日檢核程序執行狀態';
    /** @var ProjectCheckService 注入ProjectCheckService */
    public $check;

    /**
     * 建構式
     *
     * @param ProjectCheckService $check
     * @return void
    */
    public function __construct(ProjectCheckService $check)
    {
        parent::__construct();
        $this->check = $check;
    }

    /**
     * 執行命令
     *
     * @return void
    */
    public function handle()
    {
        // 檔案紀錄在 storage/logs/check.log
        $log_file_path = storage_path('logs/check.log');

        //執行排程檢查
        $process = $this->check->timeCostReport();
        $success = 0;
        $fail = 0;
        $total = count($process);
        foreach ($process as $list) {
            $list['success'] ? $success++ : $fail++;
        }

        //寫入log
        $log_info = 'TimeCostReport# ' .  date('Y-m-d H:i:s') . ",total=$total,success=$success,fail=$fail" . "\r\n";
        File::append($log_file_path, $log_info);
    }
}