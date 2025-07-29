<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:task_lists,name',
        ]);

        TaskList::create([
            'name' => $request->name,
            'color' => '#' . dechex(rand(0x000000, 0xFFFFFF)),
        ]);

        // 🔁 Передаём имя обратно через query string
        return redirect()->route('home', ['selected_list' => $request->name]);
    }

    public function destroy($id)
    {
        $list = TaskList::findOrFail($id);
        $list->delete();

        return redirect()->route('home')->with('success', 'Список удалён.');
    }
}
