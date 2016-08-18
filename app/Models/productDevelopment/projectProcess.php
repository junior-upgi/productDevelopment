<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;

class ProjectProcess extends Model
{
    protected $connection = 'DB_productDevelopment';
    protected $table = "projectProcess";
    public $timestamps = false;
}