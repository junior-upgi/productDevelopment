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
use App\Repositories\ProductDevelopment\ProjectRepository;

class ProjectController extends Controller
{
    public $common;
    public $serverData;
    public $projectRepository;

    public function __construct(
        Common $common,
        ServerData $serverData,
        ProjectRepository $projectRepository
    ) {
        $this->common = $common;
        $this->serverData = $serverData;
        $this->projectRepository = $projectRepository;
    }
    
    //
    public function projectList()
    {
        $ProjectList = $this->projectRepository
            ->getNonCompleteProject(15);

        return view('Project.ProjectList')
            ->with('ProjectList', $ProjectList);
    }

    //
    public function addProject()
    {
        if (!Role::allowRole('99')) return Redirect::route('errorRoute'); 
        $client = $this->serverData->getAllClient(); 
        $node = $this->serverData->getAllNode();
        return view('Project.AddProject')
            ->with('ClientList', $client)
            ->with('NodeList', $node);
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
        return $this->projectRepository
            ->insertData($this->projectRepository->project, $params);
    }
    //
    public function editProject($projectID)
    {
        if (!Role::allowRole('99')) {
           return Redirect::route('errorRoute');
        } 
        
        $project = $this->projectRepository->getProjectByID($projectID);
        $projectContent = $this->projectRepository->getProjectContent($projectID);
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

        return $this->projectRepository
            ->updateData($this->projectRepository->project, $params, $projectID);
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
        $result = $this->projectRepository->deleteData($this->projectRepository->project, $projectID);
        return $result;
    }
    //
    public function showProject()
    {
        return view('Project.ShowProject')
            ->with('Project', $this->projectRepository->showProjectExecute())
            ->with('Product', $this->projectRepository->vShowProduct)
            ->with('Process', $this->projectRepository->vShowProcess);
    }
}