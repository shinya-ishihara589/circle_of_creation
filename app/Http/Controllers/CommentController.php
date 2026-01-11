<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    function index()
    {
        return view('game');
    }

    function store(Request $request)
    {
        $comment = new Comment;
        $comment->name = $request->input('name');
        $comment->comment = $request->input('comment');
        $comment->save();
        $comments = Comment::get();
        return view('comments.index', compact(['comments']));
    }
    function destroy()
    {
        return 0;
    }
}
