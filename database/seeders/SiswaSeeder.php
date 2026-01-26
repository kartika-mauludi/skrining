<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelases = Kelas::inRandomOrder()
        ->limit(2)->get();

        foreach ($kelases as $kelas) {
            Siswa::factory(5)->kelas($kelas->id)->create();
        }
    }
}
