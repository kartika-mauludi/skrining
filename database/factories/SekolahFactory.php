<?php

namespace Database\Factories;

use App\Models\Guru;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sekolah>
 */
class SekolahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $guru = Guru::latest()->first();

        return [
            'guru_id' => $guru->id,
            'nama_sekolah' => fake()->firstName(),
            'no_tlp' => fake()->phoneNumber(),
            'alamat_lengkap' => fake()->address()
        ];
    }

    public function teacher($guruId)
    {
        $guru = Guru::find($guruId);
        return $this->state(function (array $attributes) use ($guru) {
            return [
                'guru_id' => $guru->id,
            ];
        });
    }
}
