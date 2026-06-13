<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScenarioController extends Controller
{
    function index()
    {
        return view('index');
    }
}
