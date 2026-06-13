<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrainstormingController extends Controller
{
    function index()
    {
        return view('brainstormings.index');
    }

    function store() {}
}
