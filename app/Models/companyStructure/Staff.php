<?php

namespace App\Models\companyStructure;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\UPGWeb\ERPStaff;

class Staff extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_companyStructure';
    protected $table = "staff";
    protected $softDelete = true;
}
