<?php

namespace App\Models\UPGWeb;

use Illuminate\Database\Eloquent\Model;

class ERPClient extends Model
{
    protected $connection = 'DB_UPGWeb';
    protected $table = "vCustomer";
}