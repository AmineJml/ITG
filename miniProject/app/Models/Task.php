<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //remove the created_at and updated_at from the table
    public $timestamps = false;

    use HasFactory;
    // The attributes that are mass assignable.
    // to prevent malicious attacks
    //check eloquent ORM

    protected $fillable = ['title', 'description', 'due_date', 'user_id'];

    // user_id foreging key from table users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
