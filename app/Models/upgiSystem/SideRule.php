<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SideRule extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_upgiSystem';
    protected $table = "sideRule";
    protected $softDelete = true;
}