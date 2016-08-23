<?php

namespace App\Models\UPGWeb;

use Illuminate\Database\Eloquent\Model;

class ERPNode extends Model
{
    protected $connection = 'DB_UPGWeb';
    protected $table = "vNode";
    public $timestamps = false;
}