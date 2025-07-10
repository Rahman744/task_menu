<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('home', compact('tasks'));
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

        return redirect()->back();
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $tasks = Task::all(); // чтобы "Today" остался
        return view('home', compact('task', 'tasks'));
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

        return redirect()->route('home');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('home');
    }
}
