<?php

namespace Database\Seeders;

use App\Models\JenisBencana;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = [
            'Banjir', 'Gempa Bumi', 'Longsor', 'Kebakaran',
            'Angin Kencang', 'Kekeringan', 'Tsunami', 'Letusan Gunung Api',
            'Banjir Bandang', 'Tanah Longsor',
        ];

        foreach ($jenis as $nama) {
            JenisBencana::firstOrCreate(['nama_bencana' => $nama]);
        }

        User::firstOrCreate(['email' => 'admin@sipda.id'], [
            'full_name' => 'Administrator SIPDA',
            'password'  => Hash::make('Admin123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        User::firstOrCreate(['email' => 'petugas@sipda.id'], [
            'full_name' => 'Petugas BPBD',
            'password'  => Hash::make('Petugas123'),
            'role'      => 'petugas',
            'is_active' => true,
        ]);
    }
}
