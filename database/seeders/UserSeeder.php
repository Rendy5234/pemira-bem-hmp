<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = [
            [
                'nim' => '210101001',
                'nama' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@student.ac.id',
                'prodi' => 'Teknik Informatika',
                'fakultas' => 'Fakultas Teknik',
                'password' => Hash::make('password123'),
            ],
            [
                'nim' => '210101002',
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.ac.id',
                'prodi' => 'Sistem Informasi',
                'fakultas' => 'Fakultas Teknik',
                'password' => Hash::make('password123'),
            ],
            [
                'nim' => '210101003',
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@student.ac.id',
                'prodi' => 'Teknik Informatika',
                'fakultas' => 'Fakultas Teknik',
                'password' => Hash::make('password123'),
            ],
            [
                'nim' => '210101004',
                'nama' => 'Dewi Lestari',
                'email' => 'dewi.lestari@student.ac.id',
                'prodi' => 'Manajemen',
                'fakultas' => 'Fakultas Ekonomi',
                'password' => Hash::make('password123'),
            ],
            [
                'nim' => '210101005',
                'nama' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@student.ac.id',
                'prodi' => 'Akuntansi',
                'fakultas' => 'Fakultas Ekonomi',
                'password' => Hash::make('password123'),
            ],
            [
                'nim' => '210101006',
                'nama' => 'Fitri Handayani',
                'email' => 'fitri.handayani@student.ac.id',
                'prodi' => 'Hukum',
                'fakultas' => 'Fakultas Hukum',
                'password' => Hash::make('password123'),
            ],
            [
                'nim' => '210101007',
                'nama' => 'Gilang Ramadhan',
                'email' => 'gilang.ramadhan@student.ac.id',
                'prodi' => 'Teknik Elektro',
                'fakultas' => 'Fakultas Teknik',
                'password' => Hash::make('password123'),
            ],
            [
                'nim' => '210101008',
                'nama' => 'Hana Pertiwi',
                'email' => 'hana.pertiwi@student.ac.id',
                'prodi' => 'Ilmu Komunikasi',
                'fakultas' => 'Fakultas Ilmu Sosial',
                'password' => Hash::make('password123'),
            ],
            [
                'nim' => '210101009',
                'nama' => 'Irfan Hakim',
                'email' => 'irfan.hakim@student.ac.id',
                'prodi' => 'Teknik Sipil',
                'fakultas' => 'Fakultas Teknik',
                'password' => Hash::make('password123'),
            ],
            [
                'nim' => '210101010',
                'nama' => 'Julia Safitri',
                'email' => 'julia.safitri@student.ac.id',
                'prodi' => 'Psikologi',
                'fakultas' => 'Fakultas Psikologi',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($mahasiswa as $data) {
            User::create($data);
        }

        $this->command->info('✅ Created 10 dummy mahasiswa');
        $this->command->info('📝 Default password: password123');
    }
}