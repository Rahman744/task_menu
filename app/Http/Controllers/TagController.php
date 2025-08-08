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

        $tag = Tag::firstOrCreate(['title' => $data['title']], $data);

        return response()->json(['success' => true, 'id' => $tag->id, 'title' => $tag->title]);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return back()->with('success', 'Deleted');
    }
}
