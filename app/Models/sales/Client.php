<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_sales';
    protected $table = "client";
    protected $softDelete = true;
}
