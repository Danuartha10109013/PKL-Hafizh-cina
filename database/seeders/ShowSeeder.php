<?php

namespace Database\Seeders;

use App\Models\ShowM;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShowM::create(
            [
                'show' => 0,
                
            ]
        );
    }
}
