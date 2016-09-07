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

    protected $primaryKey = 'ID';
    
    protected $fillable = [
        'ID', 
        'mobileSystemAccount', 
        'password', 
    ];
    protected $hidden = array('password', 'remember_token');
    /*
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
    */
}