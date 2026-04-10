<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');

        if ($tab === 'mylist') {
            if (auth()->check()) {
                $items = Item::whereHas('likes', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                    ->where('user_id', '!=', auth()->id());

                if ($keyword) {
                    $items = $items->where('item_name', 'like', "%{$keyword}%");
                }

                $items = $items->get();
            } else {
                $items = collect();
            }
        } else {
            if (auth()->check()) {
                $items = Item::where('user_id', '!=', auth()->id());
            } else {
                $items = Item::query();
            }

            if ($keyword) {
                $items = $items->where('item_name', 'like', "%{$keyword}%");
            }

            $items = $items->get();
        }

        return view('items.index', compact('items', 'tab'));
    }

    public function show($item_id)
    {
        $item = Item::with([
            'user',
            'categories',
            'likes',
            'comments.user'
        ])
        ->findOrFail($item_id);

        return view('items.show', compact('item'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('items.create', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $image_path = $request->file('item_image')->store('items', 'public');

        $item = Item::create([
            'user_id' => auth()->id(),
            'item_name' => $request->item_name,
            'brand_name' => $request->brand_name,
            'text' => $request->text,
            'price' => $request->price,
            'condition' => $request->condition,
            'item_image' => $image_path,
        ]);

        $item->categories()->attach($request->categories);

        return redirect('/');
    }
}
