<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    protected $connection = 'DB_sales';
    protected $table = "client";
    public $timestamps = false;
}