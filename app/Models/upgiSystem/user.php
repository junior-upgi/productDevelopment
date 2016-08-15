<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $connection = 'DB_upgiSystem';
    protected $table = "user";
    public $timestamps = false;
}