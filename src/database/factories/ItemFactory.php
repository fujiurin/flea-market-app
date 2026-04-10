<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),

            'item_name' => $this->faker->words(2, true),
            'brand_name' => $this->faker->optional()->company(),
            'text' => $this->faker->realText(50),
            'price' => $this->faker->numberBetween(500, 50000),
            'condition' => $this->faker->randomElement([
                '新品',
                '未使用に近い',
                '目立った傷や汚れなし',
                'やや傷や汚れあり',
                '状態が悪い'
            ]),

            'item_image' => 'dummy.jpg',

            'sold' => false,
        ];
    }

    public function sold()
    {
        return $this->state(function () {
            return [
                'sold' => true,
            ];
        });
    }

}
