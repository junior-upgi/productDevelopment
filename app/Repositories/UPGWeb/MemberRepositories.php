<?php
namespace App\Repositories\UPGWeb;

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
            return $check;
        } catch (\Exception $e) {
            return null;
        }
    }
}