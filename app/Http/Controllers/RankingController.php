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
        $ranking->time = $request->time;
        $ranking->save();
    }
}
