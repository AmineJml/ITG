<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Task;
use Illuminate\Http\Request;
use JWTAuth;

class TasksController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //once ethe user login we get their userId from the token;
    //Couldnt make it as a constant because every user has a different token
    private function getCurrentUserId(): int {
        return (int) auth()->user()->id;
    }

    public function createTask(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'due_date' => 'required|date',
            ]);
            // adding user_id to our new task
            $validatedData['user_id'] = $this->getCurrentUserId();

            $task = Task::create($validatedData);

            return response()->json([
                'status' => 'success',
                'task' => $task
            ], 200);

            return response()->json([
                'status' => 'success',
                'task' => $task
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create tasks.'
            ], 500);
        }
    }



    public function listTasks()
    {
        try {
            $userId = $this->getCurrentUserId();
            $tasks = Task::where('user_id', $userId)->get();

            return response()->json(['tasks' => $tasks]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve tasks.'
            ], 500);
        }
    }


    public function listTasksByDate()
    {
        try {
            $userId = $this->getCurrentUserId();
            $tasks = Task::where('user_id', $userId)
                         ->orderBy('due_date')
                         ->get();

            return response()->json(['tasks' => $tasks]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve tasks.'
            ], 500);
        }
    }


    public function editTask(Request $request, $id)
    {
        try {

            $validatedData = $request->validate([
                'title' => 'sometimes|string',
                'description' => 'sometimes|string',
                'due_date' => 'sometimes|date',
            ]);

            $userId = $this->getCurrentUserId();
            //validating the existence of the task
            $task = Task::where('user_id', $userId)->find($id);

            if (!$task) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Task not found or unauthorized for deletion.'
                ], 404);
            }

            $task = Task::where('user_id', $userId)
                        ->where('id', $id)
                        ->update([
                            'title' => $validatedData['title'],
                            'description' => $validatedData['description'],
                            'due_date' => $validatedData['due_date'],
                        ]);
            //to check if the returnd value have updated successfully
            $updatedTask = Task::find($id);

            return response()->json([
                'status' => 'success',
                'task' => $updatedTask
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update task or unauthorized.'
            ], 500);
        }
    }




    public function deleteTask($id)
    {
        try {
            $userId = $this->getCurrentUserId();
            $task = Task::where('user_id', $userId)->find($id);

            if (!$task) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Task not found or unauthorized for deletion.'
                ], 404);
            }

            $task->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete task or task does not exist.'
            ], 500);
        }
    }



    //filterdata
    // an api to search for data the user will enter a string, and this string will search where it matches in title and  another one for description - both can be empty
    //in the same api there will gold date inputs that indicates from timea to timeb, either both empyt or both
    // are filled to select a sepcific date

    public function filterTasks(Request $request)
    {
        try
        {
            $userId = $this->getCurrentUserId();

            $validatedData = $request->validate([
                'search_title' => 'nullable|string',
                'search_description' => 'nullable|string',
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
            ]);

            $searchTitle = $validatedData['search_title'] ?? '';
            $searchDescription = $validatedData['search_description'] ?? '';
            $fromDate = $validatedData['from_date'] ?? null;
            $toDate = $validatedData['to_date'] ?? null;

            //Search priority, Date - title - description

            // Checking date range (USER CANT ENTER ONE date and skip the other)
            if (!(($fromDate !== null && $toDate !== null) || ($fromDate === null && $toDate === null))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Both dates need to be selected or both left empty.'
                ], 422);
            }

            $tasksQuery = Task::query();
            $tasksQuery->where('user_id', $userId);

            if ($fromDate !== null && $toDate !== null) {
                $tasksQuery->whereBetween('due_date', [$fromDate, $toDate]);
            }

            $tasksQuery->where('title', 'like', '%' . $searchTitle . '%');

            $tasksQuery->where(function ($query) use ($searchDescription) {
                $query->where('description', 'like', '%' . $searchDescription . '%');
            });

            return response()->json([
                'status' => 'success',
                'tasks' => $tasksQuery->get()
            ]);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete task or task does not exist.'
                ], 500);
            }
    }


}
