<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\UploadedFile;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品を取得できる()
    {
        Item::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        $items = Item::all();
        foreach ($items as $item) {
            $response->assertSee($item->item_name);
        }
    }

    public function test_購入済み商品はsoldと表示される()
    {
        Item::factory()->create([
            'sold' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold Out');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();

        $myItem = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherItem = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertSee($otherItem->item_name);

        $response->assertDontSee($myItem->item_name);
    }

    public function test_マイリストはいいねした商品のみ表示()
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create();

        $otherItem = Item::factory()->create();

        $likedItem->likes()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');

        $response->assertSee($likedItem->item_name);

        $response->assertDontSee($otherItem->item_name);
    }


    public function test_マイリストの購入済み商品はsoldと表示()
    {
        $user = User::factory()->create();

        $item = Item::factory()->sold()->create();

        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('Sold Out');
    }

    public function test_未認証の場合は何も表示されない()
    {
        Item::factory()->count(2)->create();

        $response = $this->get('/?tab=mylist');

        $items = $response->viewData('items');

        $this->assertCount(0, $items);
    }

    public function test_商品名で部分一致検索ができる()
    {
        $hitItem = Item::factory()->create([
            'item_name' => 'りんごジュース',
        ]);

        $noHitItem = Item::factory()->create([
            'item_name' => 'バナナ',
        ]);

        $response = $this->get('/?keyword=りんご');

        $response->assertSee($hitItem->item_name);
        $response->assertDontSee($noHitItem->item_name);
    }

    public function test_検索状態がマイリストでも保持される()
    {
        $user = User::factory()->create();

        $item1 = Item::factory()->create([
            'item_name' => 'りんごジュース',
        ]);

        $item2 = Item::factory()->create([
            'item_name' => 'バナナ',
        ]);

        $item1->likes()->create(['user_id' => $user->id]);
        $item2->likes()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->get('/?tab=mylist&keyword=りんご');

        $response->assertSee($item1->item_name);
        $response->assertDontSee($item2->item_name);
    }

    public function test_必要な情報が表示される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'item_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'text' => 'テスト説明',
            'price' => 1000,
            'condition' => '新品',
            'item_image' => 'test.jpg',
        ]);

        $category = Category::create(['name' => '家電']);
        $item->categories()->attach($category);

        $commentUser = User::factory()->create();
        $item->comments()->create([
            'user_id' => $commentUser->id,
            'content' => 'いい商品です',
        ]);

        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);

        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('テスト説明');
        $response->assertSee('¥1,000');
        $response->assertSee('新品');
        $response->assertSee('家電');
        $response->assertSee('いい商品です');
        $response->assertSee($commentUser->name);
    }

    public function test_複数カテゴリが表示()
    {
        $item = Item::factory()->create();

        $category1 = Category::create(['name' => '家電']);
        $category2 = Category::create(['name' => '家具']);

        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get("/item/{$item->id}");

        $response->assertSee('家電');
        $response->assertSee('家具');
    }

    public function test_いいねした商品として登録()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->post('/likes', [
            'item_id' => $item->id,
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('1');
    }

    public function test_追加済みのアイコンは色が変化()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get("/item/{$item->id}");

        $response->assertSee('ハートロゴ_ピンク.png');
        $response->assertDontSee('ハートロゴ_デフォルト.png');
}

    public function test_いいね解除できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->delete("/likes/{$item->id}");

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertSee('0');
        $response->assertSee('ハートロゴ_デフォルト.png');
    }

    public function test_ログインユーザーはコメントできる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->post('/comments', [
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);
    }

    public function test_未ログインユーザーはコメントできない()
    {
        $item = Item::factory()->create();

        $this->post('/comments', [
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_コメント未入力はエラー表示()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/comments', [
            'item_id' => $item->id,
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_コメントが255文字以上はエラー表示()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $longText = str_repeat('あ', 256);

        $response = $this->post('/comments', [
            'item_id' => $item->id,
            'content' => $longText,
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_商品出品で必要情報保存()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $category = Category::create([
            'name' => '家電',
        ]);

        $response = $this->post('/sell', [
            'categories' => [$category->id],
            'condition' => '新品',
            'item_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'text' => 'テスト説明',
            'price' => 3000,
            'item_image' => UploadedFile::fake()->create('test.jpg'),
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'item_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'text' => 'テスト説明',
            'price' => 3000,
            'condition' => '新品',
        ]);

        $item = \App\Models\Item::first();
        $this->assertTrue($item->categories->contains($category));

        $response->assertRedirect();
}
}
