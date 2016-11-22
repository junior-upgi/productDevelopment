<?php

namespace App\Models\companyStructure;

use Illuminate\Database\Eloquent\Model;

class VStaff extends Model
{
    protected $connection = "DB_UPGWeb";
    protected $table = "vStaffNode";
    public $keyType = 'string';
}
