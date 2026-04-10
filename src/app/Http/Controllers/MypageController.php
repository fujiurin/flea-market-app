<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\ProfileRequest;

class MypageController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return redirect('/mypage/profile');
        }

        $page = $request->page;

        if ($page === 'buy') {
            $items = Purchase::where('user_id', $user->id)
                ->with('item')
                ->get()
                ->pluck('item');
        } else {
            $items = Item::where('user_id', $user->id)->get();
            $page = 'sell';
        }

        return view('mypage.index', compact('user', 'profile', 'items', 'page'));
    }

    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('mypage.profile', compact('user', 'profile'));
    }


    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $isFirst = !$user->profile;

        $user->update([
                'name' => $request->name
        ]);

        $profile = $user->profile;
        $imagePath = $profile->profile_image ?? null;

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
        }

        Profile::updateOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'profile_image' => $imagePath,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        );

        if ($isFirst) {
            return redirect('/');
        } else {
            return redirect('/mypage');
        }
    }
}
