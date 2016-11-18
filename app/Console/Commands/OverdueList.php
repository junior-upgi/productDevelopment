<?php
/**
 * 寄送逾期清單給主管
 * 配合排程進行推播
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/11/04
 * @since 1.0.0 spark: 於此版本開始編寫註解
*/
namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use App\Service\ProjectCheckService;

/**
 * Class overdueList
 *
 * @package App\Console\Commands
*/
class OverdueList extends Command
{
    /** @var string 命令名稱 */
    protected $signature = 'overdueList';
    /** @var string 命令描述 */
    protected $description = '[檢核]寄送逾期清單';
    /** @var ProjectCheckService 注入ProjectCheckService */
    private $check;

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
        $this->check->sendOverdue();

        //寫入log
        $log_info = 'sendOverdue# ' .  date('Y-m-d H:i:s') . "\r\n";
        File::append($log_file_path, $log_info);
    }
}