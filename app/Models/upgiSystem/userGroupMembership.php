<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;

class userGroupMembership extends Model
{
    protected $connection = 'DB_upgiSystem';
    protected $table = "userGroupMembership";
    public $timestamps = false;
}