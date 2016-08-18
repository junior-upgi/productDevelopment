<?php

namespace App\Models\mobileMessagingSystem;

use Illuminate\Database\Eloquent\Model;

class MessageCategory extends Model
{
    protected $connection = 'DB_mobileMessagingSystem';
    protected $table = "messageCategory";
    public $timestamps = false;
}