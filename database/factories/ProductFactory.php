<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Product::class;

     public function definition()
     {
        return [
            'name' => $this->faker->word,
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10000, 2000000), // Chỉ lưu giá trị số
            'discount' => $this->faker->randomFloat(2, 0, 50), // Giảm giá từ 0% đến 50%
            'isActive' => $this->faker->boolean, // Trạng thái hoạt động
            'user_id' => DB::table('users')->inRandomOrder()->value('id') ?? 1, // Lấy user_id ngẫu nhiên từ DB
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'category_id' => $this->faker->numberBetween(1, 5),
        ];
     }
}
