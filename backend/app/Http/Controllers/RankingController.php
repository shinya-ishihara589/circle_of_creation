<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RankingController extends Controller
{
    function update(Request $request)
    {
        $ranking = new Ranking;
        $ranking->name = $request->name ?? '匿名';
        $ranking->time = $request->time;
        $ranking->panel = $request->num;
        $ranking->difficulty = 'easy';
        $ranking->ip_address = $request->ip();
        $ranking->save();

        $num = $request->num;
        if ($num < 2) {
            $num = 2;
        } else if ($num > 5) {
            $num = 5;
        }

        $difficulty = 'easy';

        $rankingQuery = new Ranking;
        $rankingQuery = $rankingQuery->where('panel', $num);
        $rankingQuery = $rankingQuery->where('difficulty', $difficulty);
        $rankingQuery = $rankingQuery->limit(15);
        $rankingQuery = $rankingQuery->orderBy('time');
        $rankings = $rankingQuery->get();

        return response()->json($rankings);
    }

    function get()
    {
        $ranking = new Ranking;
        $rankings = $ranking->limit(10)->orderBy('time')->get();

        return response()->json(['message' => 'データを受信しました', 'response' => $rankings]);
    }
}
