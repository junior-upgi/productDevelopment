<?php

namespace App\Models\companyStructure;

use Illuminate\Database\Eloquent\Model;

class staff extends Model
{
    protected $connection = 'DB_companyStructure';
    protected $table = "staff";
    public $timestamps = false;
}