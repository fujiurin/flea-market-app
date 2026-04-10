<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_購入ボタンで購入完了()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['sold' => false]);

        $user->profile()->create([
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        $this->get("/purchase/success?item_id={$item->id}&payment=card");

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'sold' => true,
        ]);
    }

    public function test_購入後はsold表示()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['sold' => false]);

        $user->profile()->create([
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => null,
        ]);

        $this->actingAs($user);

        $this->get("/purchase/success?item_id={$item->id}&payment=card");

        $response = $this->get('/');

        $response->assertSee('Sold Out');
    }

    public function test_プロフィールに購入商品表示()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->profile()->create([
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => null,
        ]);

        $this->actingAs($user);

        $this->get("/purchase/success?item_id={$item->id}&payment=card");

        $response = $this->get('/mypage?page=buy');

        $response->assertSee($item->item_name);
    }

    public function test_支払い方法が反映()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->profile()->create([
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => null,
        ]);

        $this->actingAs($user);

        $this->get("/purchase/success?item_id={$item->id}&payment=convenience");

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'convenience',
        ]);
    }

    public function test_変更した住所が購入画面に反映()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->profile()->create([
            'postal_code' => '111-1111',
            'address' => '旧住所',
            'building' => '旧ビル',
        ]);

        $this->actingAs($user);

        $this->post("/purchase/address/{$item->id}", [
            'postal_code' => '222-2222',
            'address' => '新住所',
            'building' => '新ビル',
        ]);

        $response = $this->get("/purchase/{$item->id}");

        $response->assertSee('222-2222');
        $response->assertSee('新住所');
        $response->assertSee('新ビル');
    }

    public function test_購入時に住所が紐づいて保存される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->profile()->create([
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        $this->get("/purchase/success?item_id={$item->id}&payment=card");

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);
    }
}
