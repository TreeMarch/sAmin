<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Order;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN'); // Sử dụng Faker cho tiếng Việt

        // Tạo danh sách nhiều khách hàng trước
        $customers = [];
        foreach (range(1, 20) as $index) {
            $customers[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail(),
                'phone' => '0' . $faker->numerify('9########'), // Số điện thoại hợp lệ VN
                'total_spent' => $faker->randomFloat(2, 500000, 50000000), // Ngẫu nhiên từ 500k đến 50tr
                'created_at' => Carbon::now()->subDays(rand(1, 365)), // Ngày tạo ngẫu nhiên trong 1 năm qua
                'updated_at' => now(),
            ];
        }

        // Chèn toàn bộ vào database cùng lúc (giúp giảm số lượng truy vấn)
        Customer::insert($customers);

        // Lấy danh sách khách hàng vừa tạo
        $createdCustomers = Customer::all();

        // Tạo đơn hàng cho từng khách hàng
        foreach ($createdCustomers as $customer) {
            Order::factory(rand(1, 10))->create([
                'customer_id' => $customer->id,
            ]);
        }
    }
}
