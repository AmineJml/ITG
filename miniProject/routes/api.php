<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\TasksCategoriesController;




Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});


Route::controller(TasksController::class)->group(function () {
    Route::get('tasks', 'listTasks');
    Route::get('tasks/sort', 'listTasksByDate');
    Route::post('task', 'createTask');
    Route::put('task/{id}', 'editTask');
    Route::delete('task/{id}', 'deleteTask');
    Route::get('search', 'filterTasks');
});


Route::controller(CategoriesController::class)->group(function () {
    Route::post('category', 'createCategory');
    Route::put('category/{id}', 'editCategory');
    Route::delete('category/{id}', 'deleteCategory');
    Route::get('search', 'filterCategories');

});

Route::controller(TasksCategoriesController::class)->group(function () {
    Route::post('categorize', 'addTaskToCategory');


});
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

