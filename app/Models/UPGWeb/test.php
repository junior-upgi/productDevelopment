<?php

namespace App\Models\UPGWeb;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $connection = 'DB_UPGWeb';
    protected $table = "test";
    public $timestamps = false;
}