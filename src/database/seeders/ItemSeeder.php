<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item =[
            [
                'user_id' => 1,
                'item_name' => '腕時計',
                'brand_name' => 'Rolax',
                'text' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'condition' => '良好',
                'item_image' => 'items/Armani+Mens+Clock.jpg',
            ],
            [
                'user_id' => 1,
                'item_name' => 'HDD',
                'brand_name' => '西芝',
                'text' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'condition' => '目立った傷や汚れなし',
                'item_image' => 'items/HDD+Hard+Disk.jpg',
            ],
            [
                'user_id' => 1,
                'item_name' => '玉ねぎ3束',
                'brand_name' => 'なし',
                'text' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'condition' => 'やや傷や汚れあり',
                'item_image' => 'items/iLoveIMG+d.jpg',
            ],
            [
                'user_id' => 1,
                'item_name' => '革靴',
                'brand_name' => '',
                'text' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'condition' => '状態が悪い',
                'item_image' => 'items/Leather+Shoes+Product+Photo.jpg',
            ],
            [
                'user_id' => 1,
                'item_name' => 'ノートPC',
                'brand_name' => '',
                'text' => '高性能なノートパソコン',
                'price' => 45000,
                'condition' => '良好',
                'item_image' => 'items/Living+Room+Laptop.jpg',
            ],
            [
                'user_id' => 1,
                'item_name' => 'マイク',
                'brand_name' => 'なし',
                'text' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'condition' => '目立った傷や汚れなし',
                'item_image' => 'items/Music+Mic+4632231.jpg',
            ],
            [
                'user_id' => 1,
                'item_name' => 'ショルダーバッグ',
                'brand_name' => '',
                'text' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'condition' => 'やや傷や汚れあり',
                'item_image' => 'items/Purse+fashion+pocket.jpg',
            ],
            [
                'user_id' => 1,
                'item_name' => 'タンブラー',
                'brand_name' => 'なし',
                'text' => '使いやすいタンブラー',
                'price' => 500,
                'condition' => '状態が悪い',
                'item_image' => 'items/Tumbler+souvenir.jpg',
            ],
            [
                'user_id' => 1,
                'item_name' => 'コーヒーミル',
                'brand_name' => 'Starbacks',
                'text' => '手動のコーヒーミル',
                'price' => 4000,
                'condition' => '良好',
                'item_image' => 'items/Waitress+with+Coffee+Grinder.jpg',
            ],
            [
                'user_id' => 1,
                'item_name' => 'メイクセット',
                'brand_name' => '',
                'text' => '便利なメイクアップセット',
                'price' => 2500,
                'condition' => '目立った傷や汚れなし',
                'item_image' => 'items/外出メイクアップセット.jpg',
            ],
        ];
        DB::table('items')->insert($item);   
    }
}
