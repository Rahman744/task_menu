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
        $selectedList = $request->query('list'); // Например: ?list=Work

        // Получаем все списки с подсчётом задач
        $lists = TaskList::withCount('tasks')->get();

        // Получаем задачи, включая подсчёт подзадач
        $query = Task::withCount('subtasks');

        // Если выбран список — фильтруем по нему
        if ($selectedList) {
            $query->where('list', $selectedList);
        }

        // Загружаем задачи
        $tasks = $query->get();

        // Отдаём всё в Blade
        return view('home', [
            'lists' => $lists,
            'tasks' => $tasks,
            'selectedList' => $selectedList,
        ]);
    }

    public function filterByList($id)
    {
        $tasks = Task::where('task_list_id', $id)->get();
        $taskLists = TaskList::all(); // чтобы не потерялась левая панель
        return view('home', compact('tasks', 'taskLists'));
    }


    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
        $task->load('tags', 'subtasks'); // 👈 Загрузка связей
        return response()->json($task);
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

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('home')->with('success', 'Task deleted');
    }



    public function toggle(Request $request, Task $task)
    {
        $task->is_done = $request->input('is_done') ? 1 : 0;
        $task->save();

        return response()->json(['success' => true]);
    }
}
