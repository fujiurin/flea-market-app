<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_必要な情報を取得()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $user->profile()->create([
            'profile_image' => 'test.jpg',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);

        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
            'item_name' => '出品商品',
        ]);

        $buyItem = Item::factory()->create([
            'item_name' => '購入商品',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        $responseSell = $this->get('/mypage?page=sell');
        $responseSell->assertSee('テストユーザー');
        $responseSell->assertSee('出品商品');

        $responseBuy = $this->get('/mypage?page=buy');
        $responseBuy->assertSee('購入商品');
    }

    public function test_プロフィール編集画面に初期値が表示される()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $user->profile()->create([
            'profile_image' => 'test.jpg',
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage/profile');

        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('テスト住所');
        $response->assertSee('テストビル');

        $response->assertSee('test.jpg');
    }
}
