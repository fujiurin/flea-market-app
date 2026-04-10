<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        Like::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id
        ]);

        return back();
    }

    public function destroy($itemId)
    {
        Like::where('user_id', Auth::id())
            ->where('item_id', $itemId)
            ->delete();

        return back();
    }
}
