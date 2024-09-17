<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachUserToProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * @var ProjectService
     * The service instance to handle project-related logic.
     */
    protected $projectService;

    /**
     * ProjectController constructor.
     * 
     * @param ProjectService $projectService
     * The service that handles project operations.
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }


     /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response with the list of projects and appropriate status code.
     */
    public function index()
    {
        $users = $this->projectService->getAll();
        return response()->json([
            'status' => 'success',
            'message' => 'Project retrieved successfully',
            'users' => ProjectResource::collection($users),
        ], 200); // OK
    }


    /**
     * Store a newly created project in storage.
     *
     * @param StoreProjectRequest $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response with the newly created project and appropriate status code.
     */
    public function store(StoreProjectRequest $request)
    {
        $validatedData = $request->validated();
        $project_information = $this->projectService->CreateProject($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'project created successfully',
            'Project Information' => $project_information,
        ], 201); // Created
    }

     /**
     * Display the specified project.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response with the project details and appropriate status code.
     */
    public function show(string $id)
    {
        $user = $this->projectService->showProject($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Project retrieved successfully',
            'Project' => ProjectResource::make($user),
        ], 200); // OK
    }

      /**
     * Update the specified project in storage.
     *
     * @param UpdateProjectRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response with the updated project and appropriate status code.
     */
    public function update(UpdateProjectRequest $request, String $id)
    {
        $project=Project::findOrFail($id);
        if(!$project->exists)
        {
            return $this->notFound('project not found.');
        }
        $validatedRequest = $request->validated();
        $updatedProjectResource = $this->projectService->updateProject($project, $validatedRequest);
        return response()->json([
            'status' => 'success',
            'message' => 'projrct updated successfully',
            'project' => $updatedProjectResource,
        ], 200); // OK
    }

       /**
     * Remove the task from storage.
     */
    public function destroy(String $id)
    {
        $this->projectService->deleteProject($id);
        return response()->json([
            'status' => 'success',
            'message' => 'project deleted successfully',
            'task' => [],
        ], 200); // OK
    }

    /**
     * function to attach user to project in project_user table(pivot table)
     * @param request
     * @param id of project we want to add user to it
     * @return JSON response
     */
    public function attachUserToProject(AttachUserToProjectRequest $request, $id)
    {   $project=Project::findOrFail($id);
        $validated = $request->validated();

        $this->projectService->attachUserToProject($project, $validated);

        return response()->json(['message' => 'User attached to project successfully.']);
    }


    /**
     * function to get all tasks related to project
     * @param project
     * @return json response that contain task
     */
    public function getProjectTasks(Project $project)
    {
        $tasks = $project->tasks;
        return response()->json($tasks);
    }

    
    /**
     * function to get last and oldest task assigned to project
     * @param project
     * @return json response contain last and oldest task
     */
    public function getTaskslatestoldest(Project $project)
    {
        $latestTask = $project->latestTask;
        $oldestTask = $project->oldestTask;

        return response()->json([
            'latest_task' => $latestTask,
            'oldest_task' => $oldestTask,
        ]);
    }

    /** function to get tasks that related with project we defined as param 
    * @return json response contain last and oldest task related to project 
    * with high priority and backend title
    */
    public function getProjectTasks_highpriority(Project $project)
    {
        $latestTask = $project->latestHighPriorityBackendTask;
        $oldestTask = $project->oldestHighPriorityBackendTask;

        return response()->json([
            'latest_task' => $latestTask,
            'oldest_task' => $oldestTask,
        ]);
    }
}
