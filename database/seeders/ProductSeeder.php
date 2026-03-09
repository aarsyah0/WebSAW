<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Lego Classic Brick Box', 'description' => 'Mainan bongkar pasang kreatif untuk anak.', 'price' => 250000, 'stock' => 30, 'age_range' => '4-99', 'category' => 'Building'],
            ['name' => 'Puzzle Kayu Hewan', 'description' => 'Puzzle edukatif mengenal hewan.', 'price' => 85000, 'stock' => 25, 'age_range' => '2-6', 'category' => 'Puzzle'],
            ['name' => 'Mainan Edukasi Bentuk', 'description' => 'Mengenal bentuk dan warna.', 'price' => 65000, 'stock' => 40, 'age_range' => '1-4', 'category' => 'Edukasi'],
            ['name' => 'Action Figure Superhero', 'description' => 'Figure action untuk koleksi dan bermain peran.', 'price' => 120000, 'stock' => 20, 'age_range' => '5-12', 'category' => 'Action'],
            ['name' => 'Board Game Keluarga', 'description' => 'Permainan papan untuk seluruh keluarga.', 'price' => 180000, 'stock' => 15, 'age_range' => '6-99', 'category' => 'Board Game'],
            ['name' => 'Stuffed Toy Beruang', 'description' => 'Boneka lembut aman untuk bayi.', 'price' => 95000, 'stock' => 50, 'age_range' => '0-5', 'category' => 'Boneka'],
            ['name' => 'Set Alat Musik Mainan', 'description' => 'Piano dan drum mainan untuk pengenalan musik.', 'price' => 150000, 'stock' => 18, 'age_range' => '3-8', 'category' => 'Musik'],
            ['name' => 'Mobil Remote Control', 'description' => 'Mobil RC tahan banting untuk outdoor.', 'price' => 220000, 'stock' => 12, 'age_range' => '6-14', 'category' => 'RC'],
            ['name' => 'Blok Bayi Soft', 'description' => 'Blok empuk aman untuk bayi 0+.', 'price' => 75000, 'stock' => 35, 'age_range' => '0-3', 'category' => 'Bayi'],
            ['name' => 'Science Kit Anak', 'description' => 'Eksperimen sains sederhana untuk anak.', 'price' => 195000, 'stock' => 10, 'age_range' => '8-14', 'category' => 'Edukasi'],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['name' => $p['name']],
                array_merge($p, ['image' => null])
            );
        }
    }
}
