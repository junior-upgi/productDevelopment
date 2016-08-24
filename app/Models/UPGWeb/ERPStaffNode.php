<?php

namespace App\Models\UPGWeb;

use Illuminate\Database\Eloquent\Model;
use App\Models\companyStructure\Relationship;
use App\Models\companyStructure\Staff;

class ERPStaffNode extends Model
{
    protected $connection = 'DB_UPGWeb';
    protected $table = "vStaffNode";
    public $timestamps = false;

    public function mapping()
    {
        return $this->hasOne(Relationship::class, 'staffID', 'ID');
    }

    public function superivisor()
    {
        return $this->hasOne(Relationship::class, 'staffID', 'ID');
    }
    public function primaryDelegate()
    {
        return $this->hasOne(ERPStaffNode::class, 'ID', 'primaryDelegateID');
    }
    public function secondaryDelegate()
    {
        return $this->hasOne(ERPStaffNode::class, 'ID', 'secondaryDelegateID');
    }
}