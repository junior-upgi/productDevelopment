<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
    use SoftDeletes;
    use Authenticatable;
    
    protected $connection = 'DB_upgiSystem';
    protected $table = "user";
    protected $softDelete = true;
    /*
    protected $fillable = [
        'mobileSystemAccount', 'password',
    ];
    */
}