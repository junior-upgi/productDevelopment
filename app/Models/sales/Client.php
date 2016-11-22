<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $connection = 'DB_UPGWeb';
    protected $table = "vCustomer";
}
