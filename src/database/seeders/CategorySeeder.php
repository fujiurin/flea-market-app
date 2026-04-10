<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category =[
            ['name' => 'ファッション'],
            ['name' => '家電'],
            ['name' => 'インテリア'],
            ['name' => 'レディース'],
            ['name' => 'メンズ'],
            ['name' => 'コスメ'],
            ['name' => '本'],
            ['name' => 'ゲーム'],
            ['name' => 'スポーツ'],
            ['name' => 'キッチン'],
            ['name' => 'ハンドメイド'],
            ['name' => 'アクセサリー'],
            ['name' => 'おもちゃ'],
            ['name' => 'ベビー・キッズ'],
        ];
        DB::table('categories')->insert($category);
    }
}
