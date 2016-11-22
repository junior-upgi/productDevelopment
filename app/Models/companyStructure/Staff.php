<?php

namespace App\Models\companyStructure;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $connection = 'DB_UPGWeb';
    protected $table = "vStaff";
    public $keyType = 'string';
}
