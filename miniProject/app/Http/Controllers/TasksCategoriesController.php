<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Task_category;

use Illuminate\Http\Request;

class TasksCategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function getCurrentUserId(): int {
        return (int) auth()->user()->id;
    }

    public function addTaskToCategory(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'task_id' => 'required|int',
                'category_id' => 'required|int',
            ]);

            $taskId = $validatedData['task_id'];
            $categoryId = $validatedData['category_id'];
            $userId = $this->getCurrentUserId();

            $task = Task::where('id', $taskId)
                ->where('user_id', $userId)
                //goes directly to catch
                ->firstOrFail();

            $category = Category::where('id', $categoryId)
                ->where('user_id', $userId)
                ->firstOrFail();

            // Checking if the relation already exist
            $checkExistence = Task_category::where('task_id', $taskId)
                ->where('category_id', $categoryId)
                //code continue
                ->first();

            if ($checkExistence) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Task already belongs to this category.',
                ], 500);
            }


            $task_category = Task_category::create($validatedData);
            return response()->json([
                'status' => 'success',
                'New addition' => $task_category,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add task to category.',
            ], 500);
        }
    }

}
