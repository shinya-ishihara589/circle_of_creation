<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RadioController extends Controller
{
    function index()
    {
        return view('radios.index');
    }

    function radio($pass)
    {
        $password = config('app.radio_password');
        if ($pass === $password) {
            return view('radios.radio');
        }
        abort(404);
    }
}
