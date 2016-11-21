<?php
/**
 * client相關資料邏輯處理
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories\sales;

use App\Models\sales\Client;

/**
 * Class ClientRepositories
 *
 * @package App\Http\Controllers
 */
class ClientRepository
{
    /** @var Client 注入Client */
    private $client;

    /**
     * 建構式
     *
     * @param Client $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 取得所有廠商資料
     * 
     * @return Client 回傳Client Model
     */
    public function getAllClient()
    {
        return $this->client->orderBy('name')->get();
    }
}