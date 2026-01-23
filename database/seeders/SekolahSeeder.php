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
            Sekolah::create([
                'guru_id' => $guru->id,
                'nama_sekolah' => fake()->name(),
                'no_tlp' => fake()->phoneNumber(),
                'alamat_lengkap' => fake()->address()
            ]);
        }
    }
}
