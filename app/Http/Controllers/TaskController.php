<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNoteRequest;
use App\Http\Requests\AssignTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rules\Exists;

class TaskController extends Controller
{   protected $taskService;


    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    /**
     * Display a listing of the tasks.
     * 
     */
    public function index(Request $request)
    {   
      /*
        $priority = $request->input('priority');
        $status = $request->input('status');

        $tasks = Task::query();

        //if request has a priority apply filter scope by priority
        if ($priority) {
            $tasks->byPriority($priority);
        }
        //if request has a status apply filter scope by status
        if ($status) {
            $tasks->byStatus($status);
        }

*/
$tasks=$this->taskService->getAll();
       
        return response()->json([
            'status' => 'success',
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks->get(),
        ], 200); // OK
    }

    /**
     * Store a newly task in storage.
     */
    public function store(StoreTaskRequest $request, $projectId)
    { $validRequest=$request->validated();
      $task=$this->taskService->CreateTask($validRequest,$projectId);
   
    
     return response()->json([
        'status' => 'success',
        'message' => 'task created successfully',
        'task' => $task,
    ], 201); // Created    
    
    }
    /**
     * Display the specified task.
     */
    public function show(String $id)
    {
        $fetchedData = $this->taskService->showTask($id);
        return response()->json([
            'status' => 'success',
            'message' => 'task retrieved successfully',
            'task' => $fetchedData,
        ], 200); // OK
    }

    /**
     * Update the task in storage.
     */
    public function update(UpdateTaskRequest $request,$projectId,$taskId)
    {
        $task=Task::where('project_id', $projectId)->findOrFail($taskId);
        if(!$task->exists)
        {
            return $this->notFound('task not found.');
        }
        $validatedRequest = $request->validated();
        $updatedTaskResource = $this->taskService->updateTask($validatedRequest,$projectId,$taskId);
        return response()->json([
            'status' => 'success',
            'message' => 'task updated successfully',
            'task' => $updatedTaskResource,
        ], 200); // OK
    }

    /**
     * Remove the task from storage.
     */
    public function destroy(String $id)
    {
        $this->taskService->deleteTask($id);
        return response()->json([
            'status' => 'success',
            'message' => 'task deleted successfully',
            'task' => [],
        ], 200); // OK
    }

    public function updateStatus(UpdateTaskStatusRequest $request, $projectId, $taskId)
    {
        $task = $this->taskService->updateTaskStatus($request->validated(), $projectId, $taskId);

        return response()->json($task, 200);
    }

    public function addNote(AddNoteRequest $request, $projectId, $taskId)
    {
        $task = $this->taskService->addNoteToTask($request->validated(), $projectId, $taskId);

        return response()->json($task, 200);
    }
    
   
}
