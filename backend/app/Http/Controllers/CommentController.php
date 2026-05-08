<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    function index()
    {
        return view('index');
    }

    function game(Request $request, $num = 5)
    {
        if ($num < 2) {
            $num = 2;
        } else if ($num > 5) {
            $num = 5;
        }

        $difficulty = 'easy';

        Log::info($request->ip());
        $rankingQuery = new Ranking;
        $rankingQuery = $rankingQuery->where('panel', $num);
        $rankingQuery = $rankingQuery->where('difficulty', $difficulty);
        $rankingQuery = $rankingQuery->limit(15);
        $rankingQuery = $rankingQuery->orderBy('time');
        $rankings = $rankingQuery->get();

        return view('game', compact('rankings', 'num'));
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
