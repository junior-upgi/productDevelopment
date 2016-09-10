<?php
namespace App\Repositories\companyStructure;

use App\Models\companyStructure\Relationship;
use App\Models\companyStructure\VStaffRelationship;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Node;

class StaffRepositories
{
    protected $relationship;
    protected $vStaffRelationship;
    protected $staff;
    protected $vStaff;
    protected $node;

    public function __construct(
        Relationship $relationship,
        VStaffRelationship $vStaffRelationship,
        Staff $staff,
        VStaff $vStaff,
        Node $node
    ) {
        $this->relationship = $relationship;
        $this->vStaffRelationship = $vStaffRelationship;
        $this->staff = $staff;
        $this->vStaff = $vStaff;
        $this->node = $node;
    }
    public function getAllNode()
    {
        return $this->node->orderBy('ID')->get();
    }

    public function getStaffByNodeID($nodeID)
    {
        return $this->vStaff->where('nodeID', $nodeID)->orderBy('ID')->get();
    }
}