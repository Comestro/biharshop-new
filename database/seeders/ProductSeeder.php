<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Smartphone',
                'description' => 'Latest smartphone with amazing features',
                'price' => 15000,
                'category_id' => 1
            ],
            [
                'name' => 'T-Shirt',
                'description' => 'Comfortable cotton t-shirt',
                'price' => 499,
                'category_id' => 2
            ],
            [
                'name' => 'Table Lamp',
                'description' => 'Modern design table lamp',
                'price' => 899,
                'category_id' => 3
            ],
            [
                'name' => 'Novel',
                'description' => 'Bestselling novel',
                'price' => 299,
                'category_id' => 4
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
