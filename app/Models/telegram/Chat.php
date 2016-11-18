<?php

namespace App\Models\telegram;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //
    protected $connection = 'telegram';
    protected $table = "chat";

    protected $fillable = [
        'id',
        'type',
        'title',
    ];
}
