<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $request->item_id,
            'content' => $request->content,
        ]);

        return back();
    }
}
