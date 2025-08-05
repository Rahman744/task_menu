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
            // простой LIKE по JSON-строке — должен работать если теги сохранены как json_encode([...])
            $query->where('tags', 'LIKE', '%' . $selectedTag . '%');
        }

        $tasks = $query->get();

        // Добавим для каждого task удобное поле tags_array — массив тегов
        $tasks->each(function ($task) {
            $task->tags_array = [];
            if ($task->tags) {
                $decoded = json_decode($task->tags, true);
                if (is_array($decoded)) {
                    $task->tags_array = $decoded;
                } else {
                    // на случай, если в базе CSV
                    $task->tags_array = array_values(array_filter(array_map('trim', explode(',', $task->tags))));
                }
            }
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
            // теперь принимаем tags как nullable (может быть строкой или массивом)
            'tags' => 'nullable',
            'subtasks' => 'nullable|array',
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->list = $request->list;
        $task->due_date = $request->due_date;

        // --- Обработка поля tags: может быть массивом или строкой CSV ---
        $incoming = $request->input('tags', null);

        if (is_null($incoming) || $incoming === '') {
            $tags = [];
        } elseif (is_array($incoming)) {
            $tags = $incoming;
        } else {
            // строка: разбиваем по запятой
            $tags = array_map('trim', explode(',', $incoming));
        }

        // оставляем только непустые значения
        $tags = array_values(array_filter($tags, fn($t) => $t !== null && $t !== ''));

        // сохраняем как JSON (или NULL если пусто)
        $task->tags = count($tags) ? json_encode($tags, JSON_UNESCAPED_UNICODE) : null;

        $task->save();

        // Сохраняем подзадачи (если есть)
        if ($request->has('subtasks') && is_array($request->subtasks)) {
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
            'subtasks' => 'nullable|array',
        ]);

        $task = Task::findOrFail($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->list = $request->list;
        $task->due_date = $request->due_date;

        // --- Обработка tags (как в store) ---
        $incoming = $request->input('tags', null);

        if (is_null($incoming) || $incoming === '') {
            $tags = [];
        } elseif (is_array($incoming)) {
            $tags = $incoming;
        } else {
            $tags = array_map('trim', explode(',', $incoming));
        }

        $tags = array_values(array_filter($tags, fn($t) => $t !== null && $t !== ''));

        $task->tags = count($tags) ? json_encode($tags, JSON_UNESCAPED_UNICODE) : null;

        $task->save();

        // Обновляем подзадачи: удаляем старые и создаём новые
        $task->subtasks()->delete();
        if ($request->has('subtasks') && is_array($request->subtasks)) {
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
