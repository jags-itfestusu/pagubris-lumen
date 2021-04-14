<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Category::factory()->state([
            'name' => 'Elektronik'
        ])->create();
        Category::factory()->state([
            'name' => 'Peliharaan'
        ])->create();
        Category::factory()->state([
            'name' => 'DIY'
        ])->create();
        Category::factory()->state([
            'name' => 'Edukasi'
        ])->create();
        Category::factory()->state([
            'name' => 'Seni'
        ])->create();
        Category::factory()->state([
            'name' => 'Travel'
        ])->create();
        Category::factory()->state([
            'name' => 'Kesehatan'
        ])->create();
        Category::factory()->state([
            'name' => 'Dunia Kerja'
        ])->create();
        Category::factory()->state([
            'name' => 'Lainnya'
        ])->create();
    }
}
