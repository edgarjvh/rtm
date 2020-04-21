<?php

namespace App\Http\Controllers;

use App\Organization;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function checkorg(Request $request){
        $org = trim($request->all()['orgName']);

        $exits = Organization::where('name', 'like', '%' . $org . '%')->orderBy('name', 'asc')->get();

        return $exits;
    }

    public function setOrg(){
        if (!Auth::user()){
            return redirect('/login');
        }

        $owner = Auth::user() ? Auth::user()->organization_owner : 1;
        $orgs = Organization::orderBy('name', 'asc')->get();

        return view('regorg')->with(['owner' => $owner, 'orgs' => $orgs]);
    }

    public function saveOrganization(Request $request){
        $user = Auth::user();
        $org = trim($request->all()['organization']);

//        dd(isset($user->organization_owner));
        if (isset($user->organization_owner)){
            if ($user->organization_owner == 0){
                User::where('id', $user->id)->update([
                    'organization_id' => $org
                ]);

                return redirect('/home');
            }else{
                $organization = Organization::create([
                    'name' => $org
                ]);

                User::where('id', $user->id)->update(['organization_id' => $organization->id]);

                return redirect('/home');
            }
        }else{
            if ($_SESSION['organization_owner'] == 0){
                User::where('id', $user->id)->update([
                    'organization_id' => $org,
                    'organization_owner' => $_SESSION['organization_owner']
                ]);

                return redirect('/home');
            }else{
                $organization = Organization::create([
                    'name' => $org
                ]);

                User::where('id', $user->id)->update([
                    'organization_id' => $organization->id,
                    'organization_owner' => $_SESSION['organization_owner']
                ]);

                return redirect('/home');
            }
        }
    }
}
