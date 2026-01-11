<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\KategoriPemilihan;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Event 1: Pemilihan Raya 2024/2025
        $event1 = Event::create([
            'nama_event' => 'Pemilihan Raya BEM & HMP 2024',
            'periode' => '2024/2025',
            'tanggal_mulai' => '2024-03-01',
            'tanggal_selesai' => '2024-03-05',
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '17:00:00',
            'deskripsi' => 'Pemilihan Ketua BEM dan HMP periode 2024/2025',
            'status' => 'aktif'
        ]);

        // Kategori untuk event 1
        KategoriPemilihan::create([
            'id_event' => $event1->id_event,
            'nama_kategori' => 'Ketua BEM',
            'jenis' => 'BEM',
            'deskripsi' => 'Pemilihan Ketua BEM Universitas'
        ]);

        KategoriPemilihan::create([
            'id_event' => $event1->id_event,
            'nama_kategori' => 'Ketua HMP Teknik Informatika',
            'jenis' => 'HMP',
            'deskripsi' => 'Pemilihan Ketua HMP Prodi Teknik Informatika'
        ]);

        KategoriPemilihan::create([
            'id_event' => $event1->id_event,
            'nama_kategori' => 'Ketua HMP Sistem Informasi',
            'jenis' => 'HMP',
            'deskripsi' => 'Pemilihan Ketua HMP Prodi Sistem Informasi'
        ]);

        // Event 2: Draft
        $event2 = Event::create([
            'nama_event' => 'Pemilihan Raya BEM & HMP 2025',
            'periode' => '2025/2026',
            'tanggal_mulai' => '2025-03-01',
            'tanggal_selesai' => '2025-03-05',
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '17:00:00',
            'deskripsi' => 'Pemilihan periode selanjutnya',
            'status' => 'draft'
        ]);
    }
}