<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kelas = Kelas::latest()->first();

        return [
            'kelas_id' => $kelas->id,
            'no_absen' => fake()->numberBetween(1, 99),
            'nis' => fake()->numberBetween(0, 999),
            'nama_lengkap' => fake()->name(),
            'tgl_lahir' => fake()->date(),
            'tempat_lahir' => fake()->city(),
            'alamat' => fake()->address(),
            'nama_wali' => fake()->name(),
            'no_tlp_wali' => fake()->phoneNumber()
        ];
    }

    public function kelas($kelasId)
    {
        $kelas = Kelas::find($kelasId);
        return $this->state(function (array $attributes) use ($kelas) {
            return [
                'kelas_id' => $kelas->id,
            ];
        });
    }
}
