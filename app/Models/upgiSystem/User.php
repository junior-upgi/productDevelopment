<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

use Carbon\Carbon;

use App\Models\companyStructure\VStaff;

class User extends Model implements AuthenticatableContract
{
    use SoftDeletes;
    use Authenticatable;
    
    protected $connection = 'DB_upgiSystem';
    protected $table = "user";
    protected $softDelete = true;

    protected $primaryKey = 'ID';
    public $incrementing = false;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = [
        'ID', 
        'mobileSystemAccount', 
        'password', 
        'authorization',
        'erpID',
    ];
    protected $hidden = array('password', 'remember_token');
    
    public function staff()
    {
        return $this->hasOne(VStaff::class, 'ID', 'erpID');
    }
}
