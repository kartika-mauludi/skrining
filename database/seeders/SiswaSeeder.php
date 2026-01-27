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
            Siswa::create([
                'kelas_id' => $kelas->id,
                'no_absen' => fake()->numberBetween(0, 199),
                'nis' => fake()->numberBetween(0, 999),
                'nama_lengkap' => fake()->name(),
                'tgl_lahir' => fake()->date(),
                'tempat_lahir' => fake()->city(),
                'alamat' => fake()->address(),
                'nama_wali' => fake()->name(),
                'no_tlp_wali' => fake()->phoneNumber()
            ]);
        }
    }
}
