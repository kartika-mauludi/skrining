<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sekolahs = Sekolah::inRandomOrder()
        ->limit(5)->get();

        foreach ($sekolahs as $sekolah) {
            Kelas::factory(3)->school($sekolah->id)->create();
        }
    }
}
