<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'list',
        'due_date',
        'tags'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // В Task.php
    public function subtasks()
    {
        return $this->hasMany(Subtask::class, 'task_id');
    }
}
