<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criterias = Criteria::all()->keyBy('name');
        $products = Product::with('criterias')->get();

        $values = [
            'Lego Classic Brick Box' => ['Harga' => 250000, 'Kualitas' => 5, 'Keamanan' => 5, 'Edukasi' => 5, 'Popularitas' => 5],
            'Puzzle Kayu Hewan' => ['Harga' => 85000, 'Kualitas' => 4, 'Keamanan' => 5, 'Edukasi' => 5, 'Popularitas' => 4],
            'Mainan Edukasi Bentuk' => ['Harga' => 65000, 'Kualitas' => 4, 'Keamanan' => 5, 'Edukasi' => 5, 'Popularitas' => 4],
            'Action Figure Superhero' => ['Harga' => 120000, 'Kualitas' => 4, 'Keamanan' => 4, 'Edukasi' => 3, 'Popularitas' => 5],
            'Board Game Keluarga' => ['Harga' => 180000, 'Kualitas' => 5, 'Keamanan' => 5, 'Edukasi' => 5, 'Popularitas' => 4],
            'Stuffed Toy Beruang' => ['Harga' => 95000, 'Kualitas' => 4, 'Keamanan' => 5, 'Edukasi' => 2, 'Popularitas' => 5],
            'Set Alat Musik Mainan' => ['Harga' => 150000, 'Kualitas' => 4, 'Keamanan' => 4, 'Edukasi' => 4, 'Popularitas' => 4],
            'Mobil Remote Control' => ['Harga' => 220000, 'Kualitas' => 4, 'Keamanan' => 4, 'Edukasi' => 2, 'Popularitas' => 5],
            'Blok Bayi Soft' => ['Harga' => 75000, 'Kualitas' => 4, 'Keamanan' => 5, 'Edukasi' => 3, 'Popularitas' => 4],
            'Science Kit Anak' => ['Harga' => 195000, 'Kualitas' => 5, 'Keamanan' => 4, 'Edukasi' => 5, 'Popularitas' => 4],
        ];

        foreach ($products as $product) {
            $row = $values[$product->name] ?? null;
            if (! $row) {
                continue;
            }
            $sync = [];
            foreach ($row as $criteriaName => $value) {
                $criteria = $criterias->get($criteriaName);
                if ($criteria) {
                    $sync[$criteria->id] = ['value' => $value];
                }
            }
            $product->criterias()->sync($sync);
        }
    }
}
