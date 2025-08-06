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
        $selectedTag = $request->query('tag');

        $lists = TaskList::withCount('tasks')->get();
        $tags = Tag::all();

        $query = Task::with('subtasks')->withCount('subtasks');

        if ($selectedList) {
            $query->where('list', $selectedList);
        }

        if ($selectedTag) {
            // Так как в БД мы сохраняем tags как JSON-строку, используем простой LIKE по JSON-строке
            $query->where('tags', 'LIKE', '%"' . $selectedTag . '"%');
        }

        $tasks = $query->get();

        // Для удобства добавим каждому $task->tags_array — массив тегов (если есть)
        $tasks->transform(function ($task) {
            $task->tags_array = [];
            if (!empty($task->tags)) {
                $decoded = json_decode($task->tags, true);
                if (is_array($decoded)) {
                    $task->tags_array = $decoded;
                } else {
                    // Если вдруг хранится как "a,b"
                    $task->tags_array = array_values(array_filter(array_map('trim', explode(',', $task->tags))));
                }
            }
            return $task;
        });

        return view('home', [
            'lists' => $lists,
            'tasks' => $tasks,
            'selectedList' => $selectedList,
            'selectedTag' => $selectedTag,
            'tags' => $tags,
        ]);
    }


    public function show($id)
    {
        $task = Task::with(['subtasks'])->findOrFail($id);

        // Если в базе tags хранится JSON-массив или строка с запятыми — разберём
        $tagsArray = [];
        if (!empty($task->tags)) {
            // попробуем декодировать JSON
            $decoded = json_decode($task->tags, true);
            if (is_array($decoded)) {
                $tagsArray = array_values(array_filter(array_map('trim', $decoded), fn($v) => $v !== ''));
            } else {
                // если не JSON — попробуем разделить по запятой
                $tagsArray = array_values(array_filter(array_map('trim', explode(',', $task->tags)), fn($v) => $v !== ''));
            }
        }

        // Добавляем поле для фронтенда
        $task->tags_array = $tagsArray;

        return response()->json($task);
    }



    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'list' => 'nullable|string',
            'due_date' => 'nullable|date|before:2100-01-01',
            'tags' => 'nullable|array', // ожидаем массив tags[]
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->list = $request->list;
        $task->due_date = $request->due_date;

        // tags[] приходит из формы: чистим и сохраняем как JSON или null
        $tags = $request->input('tags', []);
        $tags = array_values(array_filter(array_map('trim', (array)$tags), fn($t) => $t !== ''));
        $task->tags = count($tags) ? json_encode($tags, JSON_UNESCAPED_UNICODE) : null;

        $task->save();

        // Сохраняем подзадачи, если используешь их отдельно
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
        ]);

        $task = Task::findOrFail($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->list = $request->list;
        $task->due_date = $request->due_date;

        $tags = $request->input('tags', []);
        $tags = array_values(array_filter(array_map('trim', (array)$tags), fn($t) => $t !== ''));
        $task->tags = count($tags) ? json_encode($tags, JSON_UNESCAPED_UNICODE) : null;

        $task->save();

        // Обновляем подзадачи (как у тебя было)
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
