<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\Subtask;
use App\Models\Tag;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $selectedList = $request->query('list');
        $selectedTag = $request->query('tag'); // если планируешь фильтрацию по тегу

        $lists = TaskList::withCount('tasks')->get();
        $tags = Tag::all(); // 💥 Вот это добавь

        $query = Task::withCount('subtasks');

        if ($selectedList) {
            $query->where('list', $selectedList);
        }

        if ($selectedTag) {
            $query->where('tags', 'LIKE', '%' . $selectedTag . '%'); // простой фильтр по названию тега
        }

        $tasks = $query->get();

        return view('home', [
            'lists' => $lists,
            'tasks' => $tasks,
            'selectedList' => $selectedList,
            'tags' => $tags, // 💥 и это передаём во view
        ]);
    }


    public function filterByList($id)
    {
        $tasks = Task::where('task_list_id', $id)->get();
        $taskLists = TaskList::all();
        return view('home', compact('tasks', 'taskLists'));
    }

    public function show($id)
    {
        $task = Task::with(['subtasks', 'tags'])->findOrFail($id);
        return response()->json($task);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'list' => 'nullable|string',
            'due_date' => 'nullable|date|before:2100-01-01',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->list = $request->list;
        $task->due_date = $request->due_date;
        $task->save();

        // Привязка тегов
        if ($request->has('tags')) {
            $task->tags()->sync($request->tags);
        }

        // Сохраняем подзадачи
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $subtask) {
                if (!empty($subtask)) {
                    $task->subtasks()->create(['title' => $subtask]);
                }
            }
        }

        return redirect()->route('home')->withInput([]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'list' => 'nullable|string',
            'due_date' => 'nullable|date|before:2100-01-01',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $task = Task::findOrFail($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->list = $request->list;
        $task->due_date = $request->due_date;
        $task->save();

        // Обновляем теги
        if ($request->has('tags')) {
            $task->tags()->sync($request->tags);
        } else {
            $task->tags()->detach(); // Удалить все, если ничего не выбрано
        }

        // Обновляем подзадачи
        $task->subtasks()->delete();
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $subtask) {
                if (!empty($subtask)) {
                    $task->subtasks()->create(['title' => $subtask]);
                }
            }
        }

        return redirect()->route('home');
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
