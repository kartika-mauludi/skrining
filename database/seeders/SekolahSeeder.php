<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Sekolah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gurus = Guru::inRandomOrder()
        ->limit(5)->get();

        foreach ($gurus as $guru) {
            Sekolah::factory(15)->teacher($guru->id)->create();
        }
    }
}
