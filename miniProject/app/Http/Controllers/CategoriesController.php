<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Category;
use Illuminate\Http\Request;
use JWTAuth;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function getCurrentUserId(): int {
        return (int) auth()->user()->id;
    }

    public function createCategory(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',

            ]);
           //    adding user_id to our new category
            $validatedData['user_id'] = $this->getCurrentUserId();
            $category = Category::create($validatedData);

            return response()->json([
                'status' => 'success',
                'category' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create category.'
            ], 500);
        }
    }

    public function editCategory(Request $request, $id)
    {
        try {

            $validatedData = $request->validate([
                'name' => 'required|string',
            ]);

            $userId = $this->getCurrentUserId();
            //validating the existence of the category
            $category = Category::where('user_id', $userId)->find($id);

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found or unauthorized for deletion.'
                ], 404);
            }

            $category = Category::where('user_id', $userId)
                        ->where('id', $id)
                        ->update([
                            'name' => $validatedData['name'],
                        ]);
            //to check if the returnd value have updated successfully
            $updatedCategory = Category::find($id);

            return response()->json([
                'status' => 'success',
                'category' => $updatedCategory
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update category or unauthorized.'
            ], 500);
        }
    }


    public function deleteCategory($id)
    {
        try {
            $userId = $this->getCurrentUserId();
            $category = Category::where('user_id', $userId)->find($id);

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'category not found or unauthorized for deletion.'
                ], 404);
            }

            $category->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'category deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete category or category does not exist.'
            ], 500);
        }
    }


    //Search categories -- only search by name
    public function filterCategories(Request $request)
    {
        try {
            $userId = $this->getCurrentUserId();

            $validatedData = $request->validate([
                'name' => 'nullable|string',
            ]);

            $name = $validatedData['name'] ?? '';


            $categories = Category::where('user_id', $userId)
                ->where('name', 'like', '%' . $name . '%')
                ->get();

            return response()->json([
                'status' => 'success',
                'categories' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve categories.'
            ], 500);
        }
    }






}
