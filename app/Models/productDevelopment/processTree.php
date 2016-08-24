<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessTree extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_productDevelopment';
    protected $table = "processTree";
    protected $softDelete = true;
}