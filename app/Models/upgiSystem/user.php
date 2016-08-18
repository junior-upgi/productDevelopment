<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'DB_upgiSystem';
    protected $table = "user";
    public $timestamps = false;
}