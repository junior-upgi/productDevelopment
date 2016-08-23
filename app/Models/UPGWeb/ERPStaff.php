<?php

namespace App\Models\UPGWeb;

use Illuminate\Database\Eloquent\Model;
use App\Models\companyStructure\TStaff;
use App\Models\companyStructure\Staff;

class ERPStaff extends Model
{
    protected $connection = 'DB_UPGWeb';
    protected $table = "vStaff";
    public $timestamps = false;
}