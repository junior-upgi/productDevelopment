<?php

namespace App\Models\companyStructure;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $connection = 'DB_UPGWeb';
    protected $table = "vNode";
    public $keyType = 'string';
}
