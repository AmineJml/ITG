<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //remove the created_at and updated_at from the table
    public $timestamps = false;

    use HasFactory;
    protected $fillable = ['name', 'user_id'];
}
