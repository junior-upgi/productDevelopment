<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Redirect;

//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use App\Http\Controllers\Role;

//use DB
use DB;
use App\Models;
use App\Models\productDevelopment\Project;
use App\Models\productDevelopment\VProjectList;
use App\Models\productDevelopment\ProjectContent;
use App\Models\productDevelopment\VShowProject;
use App\Models\productDevelopment\VShowProduct;
use App\Models\productDevelopment\VShowProcess;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\Node;
use App\Models\sales\Client;

class ProjectController extends Controller
{
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
    */

    public function projectList()
    {
        $oProject = new VProjectList();
        $ProjectList = $oProject
            ->where('completeStatus', '<>', '2')
            ->orderBy('completeStatus')
            ->orderBy('created', 'desc')
            ->paginate(15);
        $c = Auth::check();
        return view('Project.ProjectList')
            ->with('ProjectList', $ProjectList);
    }

    public function addProject()
    {
        /*
        if (!Role::allowRole('99')) {
           return Redirect::route('errorRoute');
        } 
        */
        Role::allowRoleToRedirect('99');
        $ClientList = ServerData::getClientAll();

        $NodeList = ServerData::getNodeAll();

        return view('Project.AddProject')
            ->with('ClientList', $ClientList)
            ->with('NodeList', $NodeList);
    }

    public function insertProject(Request $request)
    {
        $ProjectNumber = $request->input('referenceNumber');
        $ProjectName = $request->input('ProjectName');
        $ClientID = $request->input('ClientID');
        $SalesID = $request->input('SalesID');
        $ProjectDeadline = $request->input('ProjectDeadline');

        //$aa = new UPGICommon();
        try {
            DB::beginTransaction();

            $oProject = new Project();
            $oProject->ID = Common::getNewGUID();
            $oProject->referenceName = $ProjectName;
            $oProject->referenceNumber = $ProjectNumber;
            $oProject->clientID = $ClientID;
            $oProject->salesID = $SalesID;
            $oProject->projectDeadline = $ProjectDeadline;
            //$oProject->created = Carbon::now();
            $oProject->save();
            
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '新增開發案成功!',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }

        return $jo;
    }
    
    public function editProject($ProjectID)
    {
        if (!Role::allowRole('99')) {
           return Redirect::route('errorRoute');
        } 
        $oProject = new VProjectList();
        $ProjectData = $oProject
            ->where('ID','=',$ProjectID)
            ->first();
        
        $oProjectContent = new ProjectContent();
        $ProjectContent = $oProjectContent
            ->where('projectID','=',$ProjectID)
            ->get();
        
        $ClientList = ServerData::getClientAll();

        $NodeList = ServerData::getNodeAll();

        $oStaff = new Staff();
        $StaffList = $oStaff
            ->where('nodeID','=',$ProjectData->nodeID)
            ->get();

        return view('Project.EditProject')
            ->with('ProjectData', $ProjectData)
            ->with('ProjectContent', $ProjectContent)
            ->with('ClientList', $ClientList)
            ->with('NodeList', $NodeList)
            ->with('StaffList', $StaffList);
    }
    
    public function updateProject(Request $request)
    {
        $params = array();
        $ProjectID = $request->input('ProjectID');
        $params['referenceNumber'] = $request->input('referenceNumber');
        $params['referenceName'] = $request->input('ProjectName');
        $params['clientID'] = $request->input('ClientID');
        $params['salesID'] = $request->input('SalesID');
        $params['projectDeadline'] = $request->input('ProjectDeadline');

        try {
            DB::beginTransaction();

            $oProject = new Project();
            $oProject = $oProject
                ->where('ID',$ProjectID);
            
            if($oProject->count() < 1)
            {
                $jo = array(
                    'success' => false,
                    'msg' => '找不到該開發案資訊!',
                );
                return $jo;
            }
            $oProject->update($params);
            
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '更新開發案成功!',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }
        
        return $jo;
    }
    
    public function getStaffByNodeID($NodeID)
    {
        $Staff = new Staff();
        $StaffList = $Staff->where('nodeID', $NodeID)->get();
        return $StaffList;
    }

    public function deleteProject($ProjectID)
    {
        if (!Role::allowRole('1|99')) {
           return array(
               'success' => false,
               'msg' => '您沒有權限使用此功能',
           );
        } 
        $Project = new Project();
        try {
            DB::beginTransaction();

            $Project->where('ID', $ProjectID)->delete();
            
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '刪除開發案成功!',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }
        
        return $jo;
    }

    public function showProject()
    {
        $Project = new VShowProject();
        $Project = $Project
            ->where('productExecute', '>', 0)
            ->orderBy('completeStatus')
            ->orderBy('startDate')
            ->get();
        $Product = new VShowProduct();
        $Process = new VShowProcess();
        return view('Project.ShowProject')
            ->with('Project', $Project)
            ->with('Product', $Product)
            ->with('Process', $Process);
    }

    public function ldap()
    {
        $domain = 'upgiad.onmircosoft.com'; //設定網域名稱
        $dn="dc=upgiad,dc=onmicrosoft,dc=com";

        $user = 'test'; //設定欲認證的帳號名稱
        $password = 'Upgi@1234'; //設定欲認證的帳號密碼

        // 使用 ldap bind 
        $ldaprdn = $user . '@' . $domain; 
        // ldap rdn or dn 
        $ldappass = $password; // 設定密碼

        // 連接至網域控制站
        $ldapconn = ldap_connect($domain) or die("無法連接至 $domain");

        // 如果要特別指定某一部網域主控站(DC)來作認證則上面改寫為
        //$ldapconn = ldap_connect('dc.domain.com) or die("無法連接至 dc.domain.com"); 
        // 或 
        // $ldapconn = ldap_connect('dc2.domain.com)or die("無法連接至 dc2.domain.com"); 

        //以下兩行務必加上，否則 Windows AD 無法在不指定 OU 下，作搜尋的動作
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        if ($ldapconn) { 
            // binding to ldap server
            echo("連結$domain".$ldaprdn.",".$ldappass."");
            $ldapbind = ldap_bind($ldapconn, $user, $password);     
            //$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);     
            //$ldapbind = ldap_bind($ldapconn);     
            // verify binding     
            if ($ldapbind) {         
                $filter = "(sAMAccountName=$user)";        
                $result = @ldap_search($ldapconn, $dn, $filter);        
                if($result==false) {
                    echo "認證失敗(找不到 $user)";        
                } else {            
                    echo "認證成功...";             
                    //取出帳號的所有資訊             
                    $entries = ldap_get_entries($ldapconn, $result);
                    $data = ldap_get_entries( $ldapconn, $result );
                    
                    echo $data ["count"] . " entries returned\n";
                    
                    for($i = 0; $i <= $data ["count"]; $i ++) {
                        for($j = 0; $j <= $data [$i] ["count"]; $j ++) {
                            echo "[$i:$j]=".$data [$i] [$j] . ": " . $data [$i] [$data [$i] [$j]] [0] . "\n";
                        }
                    }        
                }    
            } else {         
                echo "認證失敗...";     
            } 
        }
        //關閉LDAP連結
        ldap_close($ldapconn);
    }
}