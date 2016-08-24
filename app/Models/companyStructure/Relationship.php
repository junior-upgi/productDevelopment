<?php

namespace App\Models\companyStructure;

use Illuminate\Database\Eloquent\Model;

use App\Models\UPGWeb\ERPNode;
use App\Models\UPGWeb\ERPStaff;
use App\Models\UPGWeb\ERPStaffNode;

class Relationship extends Model
{
    protected $connection = "DB_companyStructure";
    protected $table = "relationship";
    public $timestamps = false;

    public function staff()
    {
        return $this->hasOne(ERPStaffNode::class, 'ID', 'staffID');
    }
    public function superivisor()
    {
        return $this->hasOne(ERPStaffNode::class, 'ID', 'superivisorID');
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