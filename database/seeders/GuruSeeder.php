<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;


class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $result = User::create([
            'name' => 'guru',
            'email' => 'guru@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru'
        ]);
        

        Guru::create([
            'email' => $result->email,
            'user_id' => $result->id,
            'nip' => '13213131',
            'nama_lengkap' => 'guru test',
            'alamat' => 'surabya, gang I',
            'tempat_lahir' => 'surabaya',
            'tgl_lahir' => '1990-01-29'
        ]);
    }
}
