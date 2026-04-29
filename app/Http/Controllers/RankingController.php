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
        $ranking->ip_address = $request->ip();
        $ranking->save();

        $ranking = new Ranking;
        $rankingData = $ranking->limit(10)->orderBy('time')->get();

        return response()->json($rankingData);
    }

    function get()
    {
        $ranking = new Ranking;
        $rankingData = $ranking->limit(10)->orderBy('time')->get();

        return response()->json(['message' => 'データを受信しました', 'response' => $rankingData]);
    }
}
