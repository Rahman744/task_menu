<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        Tag::create($data);

        return back()->with('success', 'Created');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return back()->with('success', 'Deleted');
    }
}
