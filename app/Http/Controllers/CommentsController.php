<?php

namespace App\Http\Controllers;

use App\Rating;
use App\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function saveComment(Request $request){
        $comment = new Comment();
        $comment->event_id = $request->eventid;
        $comment->comment = $request->comments;
        $comment->save();

        return response()->json(['result' => 'OK', 'data' => $request->comments]);
    }

    public function getComments(Request $request){
        $comments = Comment::where('event_id', $request->eventId)->get();

        return response()->json(['result' => 'OK', 'comments' => $comments]);
    }
}
