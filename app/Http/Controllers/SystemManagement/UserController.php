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

    public function relationshipList()
    {
        
    }

    public function groupSave()
    {
        $request = request();
        $input = $request->input();
        $tran = $this->upgi->save('group', $input);
        return $tran;
    }   
}