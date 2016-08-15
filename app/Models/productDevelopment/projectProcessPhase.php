<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;

class projectProcessPhase extends Model
{
    protected $connection = 'DB_productDevelopment';
    protected $table = "projectProcessPhase";
    public $timestamps = false;
}