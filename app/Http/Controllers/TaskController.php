<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\Subtask;


class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::all();
        $subtasks = Subtask::all();
        $lists = TaskList::all(); // <--- вот эта строка нужна

        $selectedTask = null;
        if ($request->has('task')) {
            $selectedTask = Task::find($request->task);
        }

        return view('home', compact('tasks', 'subtasks', 'selectedTask', 'lists'))->with('taskLists', $lists);
    }

    public function filterByList($id)
    {
        $tasks = Task::where('task_list_id', $id)->get();
        $taskLists = TaskList::all(); // чтобы не потерялась левая панель
        return view('home', compact('tasks', 'taskLists'));
    }


    public function show($id)
    {
        $task = Task::with('subtasks')->findOrFail($id);
        $tasks = Task::with('subtasks')->get();
        return view('home', compact('tasks', 'task')); // показать форму с задачей
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
        $task->title = $request->title;
        $task->description = $request->description;
        $task->list = $request->list;
        $task->due_date = $request->due_date;
        $task->tags = $request->tags;
        $task->save();

        // Сохраняем подзадачи
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $subtask) {
                if (!empty($subtask)) {
                    $task->subtasks()->create(['title' => $subtask]);
                }
            }
        }

        return redirect()->route('home')->withInput([]); // очищает старые значения
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
        $task->title = $request->title;
        $task->description = $request->description;
        $task->list = $request->list;
        $task->due_date = $request->due_date;
        $task->tags = $request->tags;
        $task->save();

        // Обновляем подзадачи
        $task->subtasks()->delete();
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $subtask) {
                if (!empty($subtask)) {
                    $task->subtasks()->create(['title' => $subtask]);
                }
            }
        }

        return redirect()->route('home'); // очищаем форму
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->subtasks()->delete();
        $task->delete();

        return redirect()->route('home'); // удаление и очистка формы
    }

    public function toggle($id)
    {
        $task = Task::findOrFail($id);
        $task->is_done = !$task->is_done;
        $task->save();

        return redirect()->back();
    }
}
