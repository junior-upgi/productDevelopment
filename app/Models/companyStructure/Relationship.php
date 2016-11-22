<?php

namespace App\Models\companyStructure;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\UPGWeb\ERPNode;
use App\Models\UPGWeb\ERPStaff;
use App\Models\UPGWeb\ERPStaffNode;

class Relationship extends Model
{
    use SoftDeletes;

    protected $connection = "DB_upgiSystem";
    protected $table = "relationship";
    protected $softDelete = true;

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