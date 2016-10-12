<?php

namespace App\Models\productDevelopment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectContent extends Model
{
    use SoftDeletes;

    protected $connection = 'DB_productDevelopment';
    protected $table = "projectContent";
    public $timestamps = true;
    protected $softDelete = true;
}
