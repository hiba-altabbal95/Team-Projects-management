<?php

namespace App\Services;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use Exception;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ProjectService {
    /**
    * Retrieve all project
    */
    public function getAll(){
        try{
        $projects=Project::get();
        return $projects;
        }
        catch (Exception $e) {
            // Handle any exceptions that may occur
            return [
                'status' => 'error',
                'message' => 'An error occurred while retrieving projects.',
                'errors' => $e->getMessage(),
            ];
        }

    }

    /**
     * create a new project.
     * 
     * @param array $data
     * The array containing project data including 'name', 'description'.
     * 
     * @return array
     * An array containing the project information.
     * 
     * 
     */
    public function CreateProject(array $data)
    {
        try {
         //   return Project::create($data);
    
            return Project::create([
               'name' => $data['name'],
               'description' => $data['description']??null,
          ]);
                
        } catch (Exception $e) {
            throw new Exception('Project creation failed: ' . $e->getMessage());
        }
          
    }
 

     /**
     * Retrieve a project by ID.
     * 
     * @param int $id
     * The ID of the project to be retrieved.
     * 
     * @return project
     * The project instance if found.
     * 
     * @throws \Exception
     * Throws an exception if the project is not found or if an error occurs during retrieval.
     */
    public function showProject(int $id): Project
    {
        try {
            $project = Project::findOrFail($id);
            return $project;
        } catch (ModelNotFoundException $e) {
            throw new Exception('project not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve project ' . $e->getMessage());
        }
    }
    /**
     * Update an existing project.
     * 
     * @param array $data
     * The array containing updated project data..
     * 
     * @param Project $project
     * project to be updated
     * 
     * @return array
     * The updated project as array
     * 
     * @throws \Exception
     * Throws an exception if the project is not found or if an error occurs during the update.
     */
    public function updateProject(Project $project, array $data): array
    {   try{
        // Update only the fields that are provided in the data array
        $project->update(array_filter([
            'name' => $data['name'] ?? $project->name,
            'discription' => $data['discription'] ?? $project->discription,
                       
        ]));

        // Return the updated project as a resource
        return ProjectResource::make($project)->toArray(request());
    }
    catch (Exception $e) {
        throw new Exception('Failed to update project' . $e->getMessage());
    }

}
  /**
     * Delete a project by its ID.
     * 
     * @param int $id
     * The ID of the project to delete.
     * 
     * @return void
     * 
     * @throws \Exception
     * Throws an exception if the project is not found.
     */
    public function deleteProject(int $id): void
    {
        // Find theproject by ID
        $project = Project::find($id);

        // If no project is found, throw an exception
        if (!$project) {
            throw new \Exception('task not found.');
        }

        // soft Delete the project
        $project->delete();
    }


    
    public function attachUserToProject(Project $project, array $data)
    {
        $project->users()->attach($data['user_id'], [
            'role' => $data['role'],
            'contribution_hours' => $data['contribution_hours']?? 0,
        ]);
    }
}