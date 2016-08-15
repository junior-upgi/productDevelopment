<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    protected $connection = 'DB_productDevelopment';
    protected $table = "project";
    public $timestamps = false;
}