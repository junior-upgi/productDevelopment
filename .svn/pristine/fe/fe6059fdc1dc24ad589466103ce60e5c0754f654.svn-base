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

//use Repositories
use App\Repositories\ProductDevelopment\ProjectRepositories;

class ProjectController extends Controller
{
    public $common;
    public $serverData;
    public $projectRepositories;

    public function __construct(
        Common $common,
        ServerData $serverData,
        ProjectRepositories $projectRepositories
    ) {
        $this->common = $common;
        $this->serverData = $serverData;
        $this->projectRepositories = $projectRepositories;
    }
    
    //
    public function projectList()
    {
        $ProjectList = $this->projectRepositories
            ->getNonCompleteProject(15);

        return view('Project.ProjectList')
            ->with('ProjectList', $ProjectList);
    }

    //
    public function addProject()
    {
        if (!Role::allowRole('99')) return Redirect::route('errorRoute'); 

        return view('Project.AddProject')
            ->with('ClientList', $this->serverData->getAllClient())
            ->with('NodeList', $this->serverData->getAllNode());
    }
    //
    public function insertProject(Request $request)
    {
        $params = array(
            'referenceName' => $request->input('referenceNumber'),
            'referenceNumber' => $request->input('ProjectName'),
            'clientID' => $request->input('ClientID'),
            'salesID' => $request->input('SalesID'),
            'projectDeadline' => $request->input('ProjectDeadline'),
        );
        return $this->projectRepositories
            ->insertData($this->projectRepositories->project, $params);
    }
    //
    public function editProject($projectID)
    {
        if (!Role::allowRole('99')) {
           return Redirect::route('errorRoute');
        } 
        
        $project = $this->projectRepositories->getProjectByID($projectID);
        $projectContent = $this->projectRepositories->getProjectContent($projectID);
        $client = $this->serverData->getAllClient();
        $node = $this->serverData->getAllNode();
        $nodeID = $project->nodeID;
        $staff = $this->serverData->getStaffByNodeID($nodeID);

        return view('Project.EditProject')
            ->with('ProjectData', $project)
            ->with('ProjectContent', $projectContent)
            ->with('ClientList', $client)
            ->with('NodeList', $node)
            ->with('StaffList', $staff);
    }
    //
    public function updateProject(Request $request)
    {
        $projectID = $request->input('ProjectID');
        $params = array(
            'referenceName' => $request->input('referenceNumber'),
            'referenceNumber' => $request->input('ProjectName'),
            'clientID' => $request->input('ClientID'),
            'salesID' => $request->input('SalesID'),
            'projectDeadline' => $request->input('ProjectDeadline'),
        );

        return $this->projectRepositories
            ->updateData($this->projectRepositories->project, $params, $projectID);
    }
    //
    public function getStaffByNodeID($nodeID)
    {
        return $this->serverData->getStaffByNodeID($nodeID);
    }
    //
    public function deleteProject($projectID)
    {
        if (!Role::allowRole('1|99')) {
           return array(
               'success' => false,
               'msg' => '您沒有權限使用此功能',
           );
        } 
        return $this->projectRepositories->deleteData($this->projectRepositories->project, $projectID);
    }
    //
    public function showProject()
    {
        return view('Project.ShowProject')
            ->with('Project', $this->projectRepositories->showProjectExecute())
            ->with('Product', $this->projectRepositories->vShowProduct)
            ->with('Process', $this->projectRepositories->vShowProcess);
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