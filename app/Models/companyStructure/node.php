<?php

namespace App\Models\companyStructure;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Node extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_companyStructure';
    protected $table = "node";
    protected $softDelete = true;
}