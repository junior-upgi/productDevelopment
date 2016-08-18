<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;

class ProjectContent extends Model
{
    protected $connection = 'DB_productDevelopment';
    protected $table = "projectContent";
    public $timestamps = false;
}