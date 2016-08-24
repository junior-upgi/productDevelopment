<?php

namespace App\Models\mobileMessagingSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BroadcastStatus extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_mobileMessagingSystem';
    protected $table = "broadcastStatus";
    protected $softDelete = true;
}