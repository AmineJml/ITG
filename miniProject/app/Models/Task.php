<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //remove the created_at and updated_at from the table
    public $timestamps = false;

    use HasFactory;
    protected $fillable = ['title', 'description', 'due_date', 'user_id'];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
