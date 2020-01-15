<?php

namespace App\Http\Controllers;

use App\Exclusion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExclusionsController extends Controller
{
    public function saveExclusion(Request $request)
    {
        $email = $request->email;

        $exist = Exclusion::where([
            'user_email' => Auth::user()->email,
            'email' => strtolower($email)
        ])
            ->first();

        if ($exist){
            return response()->json(['result' => 'DUPLICATED']);
        }

        $exclusion = Exclusion::create([
            'user_email' => Auth::user()->email,
            'email' => strtolower($email)
        ]);

        return response()->json(['result' => 'OK']);
    }

    public function deleteExclusion(Request $request)
    {
        $email = $request->email;

        $deletedRows = Exclusion::where([
            'user_email' => Auth::user()->email,
            'email' => strtolower($email)
        ])
            ->delete();

        return response()->json(['result' => 'OK']);
    }
}
