<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->route('profile.edit')
                ->with('error', '住所を登録してください');
        }

        return view('items.purchase', compact('item', 'user', 'profile'));
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $profile = $user->profile;

        return view('items.purchase_address', compact('item', 'profile'));
    }

    public function purchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $paymentMethod = $request->payment_method;
        $user = Auth::user();

        Stripe::setApiKey(config('services.stripe.secret'));

        $stripePaymentType = $paymentMethod === 'convenience' ? 'konbini' : 'card';

        $params = [
            'payment_method_types' => ['card', 'konbini'],
            'customer_email' => $user->email,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->item_name,
                    ],
                    'unit_amount' => (int) $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/purchase/success?item_id=' . $item->id . '&payment=' . $paymentMethod),
            'cancel_url' => url('/purchase/' . $item->id),
        ];

        if ($stripePaymentType === 'konbini') {
            $params['payment_method_options'] = [
                'konbini' => [
                    'expires_after_days' => 3,
                ],
            ];
        }

        $session = Session::create($params);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $itemId = $request->item_id;
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/login');
        }

        $profile = $user->profile ?? null;

        if (!$profile) {
            return redirect('/mypage/profile')->with('error', 'プロフィールがありません');
        }

        if (Purchase::where('user_id', $user->id)->where('item_id', $itemId)->exists()) {
            return redirect('/');
        }

        $paymentMethod = $request->payment ?? 'card';

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $itemId,
            'payment_method' => $paymentMethod,
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building ?? null,
        ]);

        Item::find($itemId)->update(['sold' => true]);

        return redirect('/');
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $profile = $user->profile ?? $user->profile()->create([]);

        $profile->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('purchase.show', ['item_id' => $item->id])
            ->with('success', '住所を更新しました');
    }
}
