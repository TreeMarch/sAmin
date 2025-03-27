<?php

namespace Database\Seeders;

use App\Models\Customer;
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

        // Tạo 10 đơn hàng
        for ($i = 0; $i < 10; $i++) {
            $user = User::inRandomOrder()->first();
            $customer = Customer::inRandomOrder()->first();
            $product = Product::inRandomOrder()->first();

            if ($customer && $product) {
                Order::create([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'quantity' => $faker->numberBetween(1, 10), // Số lượng từ 1 đến 10
                    'total_price' => $product->price * $faker->numberBetween(1, 10),
                    'status' => $faker->randomElement(['pending', 'approved', 'shipped', 'completed', 'canceled']),
                ]);
            }
        }
    }
}
