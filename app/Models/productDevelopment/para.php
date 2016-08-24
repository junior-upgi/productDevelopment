<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Para extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_productDevelopment';
    protected $table = "para";
    protected $softDelete = true;
}