<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $connection = 'DB_productDevelopment';
    protected $table = "project";
    public $timestamps = false;
}