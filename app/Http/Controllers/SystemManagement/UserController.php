<?php

namespace App\Http\Controllers\SystemManagement;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

use App\Repositories\upgiSystem\UpgiSystemrepository;


class UserController extends Controller
{
    private $common;
    private $server;
    private $upgi;

    public function __construct(
        Common $common,
        ServerData $server,
        UpgiSystemrepository $upgi
    ) {
        $this->common = $common;
        $this->server = $server;
        $this->upgi = $upgi;
    }

    public function groupList()
    {
        $request = request();
        $search = $request->input('search');
        $where = null;
        if (isset($search)) {
            $params = ['key' => 'reference', 'op' => 'like', 'value' => "%$search%"];
            $where = array();
            array_push($where, $params);
        }
        $group = $this->upgi->getList('group', $where)
            ->orderBy('reference')
            ->get();
        return view('System.Group.GroupList')
            ->with('group', $group)
            ->with('search', $search);
    }

    public function getMember()
    {
        $request = request();
        $groupID = $request->input('groupID');
        $where = [];
        array_push($where, ['key' => 'groupID', 'value' => $groupID]);
        $group = $this->upgi->getList('vUserGroupList', $where)->with('staff')->orderBy('erpID')->get()->toArray();
        if (isset($group)) {
            $list = [];
            for ($i = 0; $i < count($group); $i++) {
                $staff = $group[$i]['staff'];
                if (isset($staff)) {
                    $staff = array_except($staff, 'ID');
                    $base = $group[$i];
                    $base = array_except($base, 'staff');
                    $newMember = array_merge($base, $staff);
                    array_push($list, $newMember);
                }
            }
            return [
                'success' => true,
                'msg' => '取得成員資料',
                'data' => $list,
            ];
        } else {
            return [
                'success' => false,
                'msg' => '沒有成員資料',
            ];
        }
    }

    public function getMobileUser()
    {
        $where = [];
        $haveToken = ['key' => 'deviceToken', 'op' => '<>', 'value' => null];
        $isMember = ['key' => 'erpID', 'op' => '<>', 'value' => null];
        array_push($where, $haveToken);
        array_push($where, $isMember);
        $member = $this->upgi->getList('user', $where);
        $member = $member->with('staff')->get()->toArray();
        $list = [];
        for ($i = 0; $i < count($member); $i++) {
            $staff = $member[$i]['staff'];
            if (isset($staff)) {
                $staff = array_except($staff, 'ID');
                $base = $member[$i];
                $base = array_except($base, 'staff');
                $newMember = array_merge($base, $staff);
                array_push($list, $newMember);
            }
        }
        $json = [];
        $json['message'] = '';
        $json['value'] = $list;
        return $json;
    }

    public function groupSave()
    {
        $request = request();
        $input = $request->input();
        $tran = $this->upgi->save('group', $input);
        return $tran;
    }   

    public function removeUser()
    {
        $request = request();
        $input = $request->input();
        $erpID = $input['erpID'];
        $groupID = $input['groupID'];
        $where = [];
        array_push($where, ['key' => 'groupID', 'value' => $groupID]);
        array_push($where, ['key' => 'erpID', 'value' => $erpID]);
        $member = $this->upgi->getList('vUserGroupList', $where)->first();
        if (isset($member)) {
            $set = [
                'id' => $member->membershipID,
                'type' => 'delete',
            ];
            $result = $this->upgi->save('membership', $set);
        } else {
            $result = [
                'success' => false,
                'msg' => '找不到刪除資料!',
            ];
        }
        return $result;
    }

    public function searchMember()
    {
        $member = $this->upgi->getList('user');
        $member = $member->with('staff')->get()->toArray();
        $list = [];
        for ($i = 0; $i < count($member); $i++) {
            $staff = $member[$i]['staff'];
            if (isset($staff)) {
                $staff = array_except($staff, 'ID');
                $base = $member[$i];
                $base = array_except($base, 'staff');
                $newMember = array_merge($base, $staff);
                array_push($list, $newMember);
            }
        }
        $json = [];
        $json['message'] = '';
        $json['value'] = $list;
        return $json;
    }

    public function userJoin()
    {
        $request = request();
        $input = $request->input();
        $erpID = $input['ID'];
        $userGroupID = $input['groupID'];
        $where = [];
        array_push($where, ['key' => 'mobileSystemAccount', 'value' => $erpID]);
        $user = $this->upgi->getList('user', $where);
        $userData = $user->first();
        if (isset($userData)) {
            $userID = $userData->ID;
            $where = [];
            array_push($where, ['key' => 'userID', 'value' => $userID]);
            array_push($where, ['key' => 'userGroupID', 'value' => $userGroupID]);
            $check = $this->upgi->getList('membership', $where)->first();
            if (isset($check)) {
                return array('success' => false, 'msg' => '此人已加入群組');
            }
            $set = array(
                'id' => null,
                'type' => 'add',
                'userID' => $userID,
                'userGroupID' => $userGroupID,
            );
            $result = $this->upgi->save('membership', $set);
            $user = $user->with('staff')->first();
            $data = ['erpID' => $erpID, 'nodeName' => $user->staff->nodeName, 'name' => $user->staff->name];
            $result['data'] = $data;
            return $result;
        } else {
            $result = array(
                'success' => false,
                'msg' => '找不到該成員資料',
            );
            return $result;
        }
    }
}