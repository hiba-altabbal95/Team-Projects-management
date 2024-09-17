<?php

namespace App\Services;

use App\Http\Resources\TaskResource;
use Exception;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class TaskService {
    /**
    * Retrieve all tasks
    */
    public function getAll(){
        try{
        $tasks=Task::get();
        return $tasks;
        }
        catch (Exception $e) {
            // Handle any exceptions that may occur
            return [
                'status' => 'error',
                'message' => 'An error occurred while retrieving tasks.',
                'errors' => $e->getMessage(),
            ];
        }

    }
    
    /**
     * create a new task.
     * 
     * @param array $data
     * The array containing task data 
     * @param projectid 
     * 
     * @return array
     * An array containing the task information.
     * 
     * 
     */
    public function CreateTask(array $data,$projectId)
    {
        try {
   
       
          return Task::create([
             'project_id' =>$projectId ,
             'title' => $data['title'],
             'description' => $data['description']??null,
             'status' => $data['status'],
             'priority' => $data['priority'],
             'date_due' => $data['date_due']??null,
             
          ]);
               
        } catch (Exception $e) {
            throw new Exception('Task creation failed: ' . $e->getMessage());
        }
          
    }

     /**
     * Retrieve a task by ID.
     * 
     * @param int $id
     * The ID of the task to be retrieved.
     * 
     * @return task
     * The task instance if found.
     * 
     * @throws \Exception
     * Throws an exception if the task is not found or if an error occurs during retrieval.
     */
    public function showTask(int $id): Task
    {
        try {
            $task = Task::findOrFail($id);
            return $task;
        } catch (ModelNotFoundException $e) {
            throw new Exception('task not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve task ' . $e->getMessage());
        }
    }
 
     /**
     * Update an existing task.
     * 
     * @param array $data
     * The array containing updated task data..
     * 
     * @param taskid
     * task to be updated
     * 
     * @param project we want to update
     * @return array
     * The updated task as array
     * 
     * @throws \Exception
     * Throws an exception if the task is not found or if an error occurs during the update.
     */
    public function updateTask(Array $data,$projectId, $taskId): array
    {   try{
        $task = Task::where('project_id', $projectId)->findOrFail($taskId);
        // Update only the fields that are provided in the data array
        $task->update(array_filter([
            'title' => $data['title'] ?? $task->title,
            'description' => $data['description'] ?? $task->description,
            'status'=>$data['status']??$task->status,
            'priority' => $data['priority'] ?? $task->priority,
            'date_due' => $data['date_due'] ?? $task->date_due,
           
        ]));

        // Return the updated task as a resource
        return TaskResource::make($task)->toArray(request());
    }
    catch (Exception $e) {
        throw new Exception('Failed to update task ' . $e->getMessage());
    }

}
  /**
     * Delete a task by its ID.
     * 
     * @param int $id
     * The ID of the task to delete.
     * 
     * @return void
     * 
     * @throws \Exception
     * Throws an exception if the task is not found.
     */
    public function deleteTask(int $id): void
    {
        // Find the task by ID
        $task = task::find($id);

        // If no movie is found, throw an exception
        if (!$task) {
            throw new \Exception('task not found.');
        }

        // soft Delete the task
        $task->delete();
    }

    public function updateTaskStatus(array $data, $projectId, $taskId)
    {
        $task = Task::where('project_id', $projectId)->findOrFail($taskId);
        $task->update($data);

        return $task;
    }

    
    public function addNoteToTask( $data, $projectId, $taskId)
    {
        $task = Task::where('project_id', $projectId)->findOrFail($taskId);
        $task->update(array_filter([
            'note' => $data['note'] ,
      //  $task->note=$data;
        ]));
        return $task;
    }


}