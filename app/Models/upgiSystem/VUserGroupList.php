<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;

use App\Models\companyStructure\VStaff;

class VUserGroupList extends Model
{   
    protected $connection = 'DB_upgiSystem';
    protected $table = "vUserGroupList";

    public function staff()
    {
        return $this->hasOne(VStaff::class, 'ID', 'mobileSystemAccount');
    }
}