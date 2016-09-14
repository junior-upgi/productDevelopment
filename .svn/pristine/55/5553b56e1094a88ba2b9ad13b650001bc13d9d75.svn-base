<?php
namespace App\Repositories\companyStructure;

use App\Models\companyStructure\Relationship;
use App\Models\companyStructure\VStaffRelationship;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Node;
use App\Models\upgiSystem\VUserGroupList;
use App\Models\upgiSystem\User;

class StaffRepositories
{
    public $relationship;
    public $vStaffRelationship;
    public $staff;
    public $vStaff;
    public $node;
    public $user;
    public $vUserGroupList;

    public function __construct(
        Relationship $relationship,
        VStaffRelationship $vStaffRelationship,
        Staff $staff,
        VStaff $vStaff,
        Node $node,
        User $user,
        VUserGroupList $vUserGroupList
    ) {
        $this->relationship = $relationship;
        $this->vStaffRelationship = $vStaffRelationship;
        $this->staff = $staff;
        $this->vStaff = $vStaff;
        $this->node = $node;
        $this->user = $user;
        $this->vUserGroupList = $vUserGroupList;
    }
    public function getAllNode()
    {
        return $this->node->orderBy('ID')->get();
    }

    public function getStaffByNodeID($nodeID)
    {
        return $this->vStaff->where('nodeID', $nodeID)->orderBy('ID')->get();
    }

    public function getUser($id)
    {
        return $this->user->where('mobileSystemAccount', $id)->first();
    }
}