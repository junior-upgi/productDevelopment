<?php

namespace App\Models\companyStructure;

use Illuminate\Database\Eloquent\Model;
use App\Models\UPGWeb\ERPStaff;

class Staff extends Model
{
    protected $connection = 'DB_companyStructure';
    protected $table = "staff";
    public $timestamps = false;
}