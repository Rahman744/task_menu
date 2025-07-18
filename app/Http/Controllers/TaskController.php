<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Subtask;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::with('subtasks')->get(); // загружаем задачи вместе с подзадачами
        $task = null;

        if ($request->has('task')) {
            $task = Task::with('subtasks')->find($request->input('task'));
        }

        return view('home', compact('tasks', 'task'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'list' => 'nullable|string',
            'due_date' => 'nullable|date|before:2100-01-01',
            'tags' => 'nullable|string',
        ]);

        $task = new Task();
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->list = $request->input('list');
        $task->due_date = $request->input('due_date');
        $task->tags = $request->input('tags');
        $task->save();

        $subtasks = $request->input('subtasks', []);
        foreach ($subtasks as $title) {
            if (!empty($title)) {
                $task->subtasks()->create(['title' => $title]);
            }
        }

        return redirect()->route('home');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'list' => 'nullable|string',
            'due_date' => 'nullable|date|before:2100-01-01',
            'tags' => 'nullable|string',
        ]);

        $task = Task::findOrFail($id);
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->list = $request->input('list');
        $task->due_date = $request->input('due_date');
        $task->tags = $request->input('tags');
        $task->save();

        $task->subtasks()->delete();
        $subtasks = $request->input('subtasks', []);
        foreach ($subtasks as $title) {
            if (!empty($title)) {
                $task->subtasks()->create(['title' => $title]);
            }
        }

        return redirect()->route('home');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('home');
    }
}
