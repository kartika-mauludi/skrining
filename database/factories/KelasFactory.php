<?php

namespace Database\Factories;

use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sekolah = Sekolah::latest()->first();

        return [
            'sekolah_id' => $sekolah->id,
            'nama_kelas' => fake()->userName(),
            'akses_token' => Str::random(6)
        ];
    }

    public function school($sekolahId)
    {
        $sekolah = Sekolah::find($sekolahId);
        return $this->state(function (array $attributes) use ($sekolah) {
            return [
                'sekolah_id' => $sekolah->id,
            ];
        });
    }
}