<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectProcess extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_productDevelopment';
    protected $table = "projectProcess";
    public $timestamps = true;
    protected $softDelete = true;
}
