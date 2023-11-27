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

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'status' => 'error',
                'message' => "there is a miss input",
            ], 422);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'user_id' => $this->getCurrentUserId(),
        ]);

        return response()->json([
            'status' => 'success',
            'task' => $task
        ], 200);
    }


    public function listTasks()
    {
        try {
            $userId = $this->getCurrentUserId();
            $tasks = Task::where('user_id', $userId)->get();

            return response()->json(['tasks' => $tasks]);
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve tasks.'
            ], 500);
        }
    }


    public function editTask(Request $request, $id)
    {
        try {
            $userId = $this->getCurrentUserId();
            $task = Task::where('user_id', $userId)->findOrFail($id);

            $request->validate([
                'title' => 'sometimes|string',
                'description' => 'sometimes|string',
                'due_date' => 'sometimes|date',
            ]);

            $task->update($request->only('title', 'description', 'due_date'));

            return response()->json([
                'status' => 'success',
                'task' => $task
            ], 200);
        } catch (\Exception $e) {
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
        $task = Task::where('user_id', $userId)
                    ->where('id', $id)
                    ->firstOrFail();

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

    public function filterData(Request $request)
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

        // Checking date range (USER CANT ENTER ONE and skip the other)
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

        $tasks = $tasksQuery->get();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks
        ]);
    }


}
