<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Faker\Factory as Faker;


class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $user = User::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first();

        if ($user && $product) {
            Order::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $faker->numberBetween(1, 10), // Số lượng từ 1 đến 10
                'total_price' => $product->price * $faker->numberBetween(1, 10),
                'status' => $faker->randomElement(['pending', 'approved', 'shipped', 'completed', 'canceled']),
            ]);
        }
    }
}
