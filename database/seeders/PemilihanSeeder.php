<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemilihan;
use App\Models\User;
use App\Models\Event;
use App\Models\KategoriPemilihan;
use App\Models\Kandidat;
use Carbon\Carbon;

class PemilihanSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::first();
        
        if (!$event) {
            $this->command->error('❌ Event tidak ditemukan! Buat event terlebih dahulu.');
            return;
        }

        $kategori = $event->kategoriPemilihan()->first();
        
        if (!$kategori) {
            $this->command->error('❌ Kategori pemilihan tidak ditemukan! Tambahkan kategori di event.');
            return;
        }

        $kandidats = $kategori->kandidat;
        
        if ($kandidats->count() === 0) {
            $this->command->error('❌ Kandidat tidak ditemukan! Tambahkan kandidat di kategori.');
            return;
        }

        $users = User::all();
        
        if ($users->count() === 0) {
            $this->command->error('❌ User (mahasiswa) tidak ditemukan! Run UserSeeder terlebih dahulu.');
            return;
        }

        $this->command->info("📊 Event: {$event->nama_event}");
        $this->command->info("📋 Kategori: {$kategori->nama_kategori}");
        $this->command->info("🗳️ Kandidat: {$kandidats->count()} kandidat");
        $this->command->info("👥 Mahasiswa: {$users->count()} mahasiswa");
        $this->command->info("");

        $created = 0;
        
        foreach ($users as $user) {
            $kandidat = $kandidats->random();
            
            Pemilihan::create([
                'id_event' => $event->id_event,
                'id_kategori' => $kategori->id_kategori,
                'id_kandidat' => $kandidat->id_kandidat,
                'id_user' => $user->id,
                'nim' => $user->nim,
                'nama_pemilih' => $user->nama,
                'waktu_pemilihan' => Carbon::now()->subDays(rand(0, 7))->subHours(rand(0, 23)),
                'ip_address' => $this->randomIP(),
                'user_agent' => $this->randomUserAgent(),
            ]);
            
            $created++;
        }

        $this->command->info("✅ Berhasil membuat {$created} data pemilihan dummy");
        
        $this->command->info("");
        $this->command->info("📊 STATISTIK PEMILIHAN:");
        foreach ($kandidats as $kandidat) {
            $count = Pemilihan::where('id_kandidat', $kandidat->id_kandidat)->count();
            $percentage = ($count / $users->count()) * 100;
            $this->command->info("   No. {$kandidat->nomor_urut} - {$kandidat->nama_ketua}: {$count} suara ({$percentage}%)");
        }
    }

    private function randomIP()
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255);
    }

    private function randomUserAgent()
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ];

        return $userAgents[array_rand($userAgents)];
    }
}