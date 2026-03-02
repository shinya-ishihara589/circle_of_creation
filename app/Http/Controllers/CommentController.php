<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    function index(Request $request)
    {
        Log::info($request->ip());
        $ranking = new Ranking;
        $rankingData = $ranking->limit(10)->orderByDesc('time')->get();

        return view('game', compact('rankingData'));
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

    function ranking()
    {
        return Comment::count();
    }
}
