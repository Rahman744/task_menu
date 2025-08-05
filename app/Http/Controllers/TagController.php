<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $tag = new Tag();
        $tag->title = $request->title;
        $tag->save();

        return response()->json($tag);
    }
}
