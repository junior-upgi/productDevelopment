<?php

namespace App\Models\mobileMessagingSystem;

use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    protected $connection = 'DB_mobileMessagingSystem';
    protected $table = "message";
    public $timestamps = false;
}