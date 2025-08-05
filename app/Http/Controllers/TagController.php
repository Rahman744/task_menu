<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $count = Tag::count() + 1;
        $title = 'Tag ' . $count;

        // Создаём тег
        $tag = Tag::create(['title' => $title]);

        return response()->json([
            'success' => true,
            'tag' => [
                'id' => $tag->id,
                'title' => $tag->title
            ]
        ]);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
