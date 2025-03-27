<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::skip(1)->first();

        if ($user) {
            Product::factory(10)->create([
                'user_id' => $user->id,
            ]);
        } else {
            echo "No users found. Please create a user first.\n";
        }
    }
}
