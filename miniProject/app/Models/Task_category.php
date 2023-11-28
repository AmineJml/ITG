<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Task_category extends Pivot
{
    public $timestamps = false;

    //locate the tbale. laravel usually looks at the plural of the model, however here the naming is a bit confusing
    protected $table = 'tasks_categories';

    protected $fillable = ['category_id', 'task_id'];

}
