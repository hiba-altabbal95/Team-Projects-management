<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'updateLastActivity'])->group(function () {
   // Route::get('/Users',UserController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    
});




// Route for attaching a user to a project
Route::post('Projects/{project}/Users',[ProjectController::class,'attachUserToProject']);

Route::get('Projects/{project}/Tasks', [ProjectController::class, 'getProjectTasks']);

//Route to access filtered tasks
Route::get('users/{user}/tasks', [UserController::class, 'getUserTasks']);


//route for retrieving the oldest and latest tasks
Route::get('Projects/{project}/Tasks2', [ProjectController::class, 'getTaskslatestoldest']);

Route::get('Projects/{project}/Tasks3', [ProjectController::class, 'getProjectTasks_highpriority']);


//create and edit task by manager
Route::group(['middleware' => ['auth:api', 'checkProjectManager']], function () {
    Route::post('projects/{project}/tasks', [TaskController::class,'store']);
    Route::put('projects/{project}/tasks/{task}',[TaskController::class,'update']);
});
//route to edite status of role only by developer
Route::group(['middleware' => ['auth:api', 'checkDeveloperRole']], function () {
    Route::put('projects/{project}/tasks/{task}/status', [TaskController::class,'updateStatus']);
});

//route to add notes to task by tester in the project
Route::group(['middleware' => ['auth:api', 'checkTesterRole']], function () {
    Route::post('projects/{project}/tasks/{task}/notes',  [TaskController::class,'addNote']);
});
Route::apiResource('Projects', ProjectController::class);
Route::apiResource('Users', UserController::class);



