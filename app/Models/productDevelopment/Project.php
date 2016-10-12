<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_productDevelopment';
    protected $table = "project";
    protected $softDelete = true;
}
