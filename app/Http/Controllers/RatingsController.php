<?php

namespace App\Http\Controllers;

use App\Event;
use App\Rating;
use App\RatingKey;
use App\User;
use Illuminate\Http\Request;


class RatingsController extends Controller
{

    public function handleRating($rating_key, $event_id, $rate)
    {
        $key = RatingKey::where(['rating_key' => $rating_key])->first();
        $rating = new Rating();

        if ($key) {
            if ($key->status == 0) {
                $rating->event_id = $event_id;
                $rating->rating_key = $rating_key;
                $rating->rate = $rate;
                $rating->save();

                return view('events.rated')->with(['event_id' => $event_id, 'rating_key' => $rating_key]);
            } else {
                return view('error')->with(['message' => 'Rating key has already been used']);
            }
        } else {
//            return view('error')->with(['message' => 'Rating key not found']);
            return view('events.rated')->with(['event_id' => $event_id, 'rating_key' => $rating_key]);
        }
    }

    public function test(){
        $user = User::where('users.email', 'edgarjvh@gmail.com')
            ->leftJoin('organizations', 'users.organization_id', '=', 'organizations.id')
            ->select('users.*','organizations.name as organization_name')->first();

        return $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
