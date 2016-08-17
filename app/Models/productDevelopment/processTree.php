<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;

class processTree extends Model
{
    protected $connection = 'DB_productDevelopment';
    protected $table = "processTree";
    public $timestamps = false;
}