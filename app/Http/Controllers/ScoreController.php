<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function getScore($score){
        return view('score')->with(['score' => $score]);
    }
}
