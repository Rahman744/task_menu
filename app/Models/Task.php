<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'list', 'due_date', 'tags'
    ];

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }


    public function task()
    {
        return $this->belongsTo(Task::class);
    }


}

