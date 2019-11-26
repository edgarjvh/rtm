<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function checkorg(Request $request){
        $org = trim($request->all()['orgName']);

        $exits = Organization::where('name', 'like', '%' . $org . '%')->orderBy('name', 'asc')->get();

        return $exits;
    }
}
