<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * @var UserService
     * The service instance to handle user-related logic.
     */
    protected $userService;

    /**
     * UserController constructor.
     * 
     * @param UserService $userService
     * The service that handles user operations.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response with the list of users and appropriate status code.
     */
    public function index()
    {
        $users = $this->userService->getAll();
        return response()->json([
            'status' => 'success',
            'message' => 'Users retrieved successfully',
            'users' => UserResource::collection($users),
        ], 200); // OK
    }

    /**
     * Store a newly created user in storage.
     *
     * @param StoreUserRequest $request
     * @return validated data
     * 
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        return  $this->userService->RegisterUser($validatedData);
       
    }

    /**
     * Display the specified user.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response with the user details and appropriate status code.
     */
    public function show(string $id)
    {
        $user = $this->userService->getUserById($id);

        return response()->json([
            'status' => 'success',
            'message' => 'User retrieved successfully',
            'user' => UserResource::make($user),
        ], 200); // OK
    }


    /**
     * Update the specified user in storage.
     *
     * @param UpdateUserRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response with the updated user and appropriate status code.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $user = $this->userService->updateUser($validatedData, $id);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'user' => UserResource::make($user),
        ], 200); // OK
    }


    /**
     * Remove the specified user from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response with the result of the deletion and appropriate status code.
     */
    public function destroy(string $id)
    {
        $message = $this->userService->deleteUser($id);

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ], 200); // OK
    }
    /**
     * Function to retrieve all Task related to user with filter task by status or priority using whereRelation 
     * @param request request from the user
     * @param user that we want to retrieve his task with filter
     * @return JSON response 
     */
    public function getUserTasks(Request $request, User $user)
    {
        $query = $user->tasks();

        if ($request->has('status')) {
            $query->whereRelation('project', 'tasks.status', $request->input('status'));
        }

        if ($request->has('priority')) {
            $query->whereRelation('project', 'tasks.priority', $request->input('priority'));
        }

        $tasks = $query->get();

        return response()->json($tasks);
    }
}
