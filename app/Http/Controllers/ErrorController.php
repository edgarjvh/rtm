<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function showMessage(){
        return view('error')->with('message', 'Mensaje de error');
    }
}
