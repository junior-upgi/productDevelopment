<?php
namespace App\Repositories;

use App\Models\UPGWeb\ERPStaff;

class MemberRepositories
{
    public $staff;

    public function __construct(
        ERPStaff $staff
    ) {
        $this->staff = $staff;
    }

    public function checkPersonal($ID, $PersonalID)
    {
        try {
            $check = $this->staff
                ->where('ID', $ID)
                ->where('PersonalID', $PersonalID)
                ->first();
            if ($check) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}