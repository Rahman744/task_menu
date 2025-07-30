<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $fillable = ['name', 'color'];

    // 👇 ВСТАВЬ ЭТО СЮДА
    public function tasks()
    {
        return $this->hasMany(Task::class, 'list', 'name');
    }
}
