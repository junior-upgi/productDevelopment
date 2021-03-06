<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroupMembership extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_upgiSystem';
    protected $table = "userGroupMembership";
    protected $softDelete = true;
    protected $hidden = ['created', 'deprecated', 'created_at', 'updated_at', 'deleted_at'];
}
