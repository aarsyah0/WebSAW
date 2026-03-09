<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Harga', 'type' => 'cost', 'weight_order' => 1],
            ['name' => 'Kualitas', 'type' => 'benefit', 'weight_order' => 2],
            ['name' => 'Keamanan', 'type' => 'benefit', 'weight_order' => 3],
            ['name' => 'Edukasi', 'type' => 'benefit', 'weight_order' => 4],
            ['name' => 'Popularitas', 'type' => 'benefit', 'weight_order' => 5],
        ];

        foreach ($data as $row) {
            Criteria::updateOrCreate(
                ['name' => $row['name']],
                $row
            );
        }
    }
}
