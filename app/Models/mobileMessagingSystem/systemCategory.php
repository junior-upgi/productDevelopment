<?php

namespace App\Models\mobileMessagingSystem;

use Illuminate\Database\Eloquent\Model;

class SystemCategory extends Model
{
    protected $connection = 'DB_mobileMessagingSystem';
    protected $table = "systemCategory";
    public $timestamps = false;
}