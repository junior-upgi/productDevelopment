<?php
namespace App\Repositories\sales;

use App\Models\sales\Client;

class ClientRepositories
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAllClient()
    {
        return $this->client->orderBy('name')->get();
    }
}