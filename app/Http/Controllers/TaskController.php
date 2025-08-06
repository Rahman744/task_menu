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
        $selectedTag  = $request->query('tag');

        $lists = TaskList::withCount('tasks')->get();
        $tags  = Tag::all();

        $query = Task::with('subtasks')->withCount('subtasks');

        if ($selectedList) {
            $query->where('list', $selectedList);
        }

        if ($selectedTag) {
            // Сначала пробуем использовать JSON-специфичный фильтр (надежнее)
            try {
                // whereJsonContains корректно найдет элемент в JSON-массиве
                $query->whereJsonContains('tags', $selectedTag);
            } catch (\Throwable $e) {
                // Фоллбек: если СУБД или версия Laravel не поддерживает whereJsonContains,
                // используем старый LIKE по JSON-строке (не идеально, но рабочий запасной путь).
                $query->where('tags', 'LIKE', '%"' . $selectedTag . '"%');
            }
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
            $decoded = json_decode($task->tags, true);
            if (is_array($decoded)) {
                $tagsArray = array_values(array_filter(array_map('trim', $decoded), fn($v) => $v !== ''));
            } else {
                $tagsArray = array_values(array_filter(array_map('trim', explode(',', $task->tags)), fn($v) => $v !== ''));
            }
        }

        // Добавляем поля для фронтенда:
        $task->tags_array = $tagsArray; // для совместимости с предыдущим кодом
        $task->tags = $tagsArray;       // JS в IIFE ожидает именно task.tags

        return response()->json($task);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'list' => 'nullable|string',
            'due_date' => 'nullable|date|before:2100-01-01',
            'tags' => 'nullable',
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->list = $request->list;
        $task->due_date = $request->due_date;

        $tags = $this->normalizeTags($request->input('tags', []));
        $task->tags = count($tags) ? json_encode($tags, JSON_UNESCAPED_UNICODE) : null;

        $task->save();

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
            'tags' => 'nullable',
        ]);

        $task = Task::findOrFail($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->list = $request->list;
        $task->due_date = $request->due_date;

        $tags = $this->normalizeTags($request->input('tags', []));
        $task->tags = count($tags) ? json_encode($tags, JSON_UNESCAPED_UNICODE) : null;

        $task->save();

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

    /**
     * Нормализует входные теги.
     */
    protected function normalizeTags($input): array
    {
        if ($input === null) {
            return [];
        }

        if (is_string($input)) {
            $arr = array_map('trim', explode(',', $input));
            return array_values(array_filter($arr, fn($v) => $v !== ''));
        }

        if (is_array($input)) {
            $arr = array_map(function ($v) {
                return is_string($v) ? trim($v) : (is_null($v) ? '' : trim((string)$v));
            }, $input);
            return array_values(array_filter($arr, fn($v) => $v !== ''));
        }

        $str = trim((string)$input);
        if ($str === '') return [];
        return array_values(array_filter(array_map('trim', explode(',', $str)), fn($v) => $v !== ''));
    }
}
