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
            'referenceNumber' => $request->input('referenceNumber'),
            'referenceName' => $request->input('ProjectName'),
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
            'referenceNumber' => $request->input('referenceNumber'),
            'referenceName' => $request->input('ProjectName'),
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
        $result = $this->projectRepositories->deleteData($this->projectRepositories->project, $projectID);
        return $result;
    }
    //
    public function showProject()
    {
        return view('Project.ShowProject')
            ->with('Project', $this->projectRepositories->showProjectExecute())
            ->with('Product', $this->projectRepositories->vShowProduct)
            ->with('Process', $this->projectRepositories->vShowProcess);
    }
}