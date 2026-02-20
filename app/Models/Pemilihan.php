<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemilihan extends Model
{
    use SoftDeletes;

    protected $table = 'tb_pemilihan';
    protected $primaryKey = 'id_pemilihan';
    
    protected $fillable = [
        'id_event',
        'id_kategori',
        'id_kandidat',
        'id_user',
        'nim',
        'nama_pemilih',
        'waktu_pemilihan',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'waktu_pemilihan' => 'datetime',
    ];

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event', 'id_event');
    }

    // Relasi ke Kategori Pemilihan
    public function kategoriPemilihan()
    {
        return $this->belongsTo(KategoriPemilihan::class, 'id_kategori', 'id_kategori');
    }

    // Relasi ke Kandidat
    public function kandidat()
    {
        return $this->belongsTo(Kandidat::class, 'id_kandidat', 'id_kandidat');
    }

    // Relasi ke User (Mahasiswa)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Helper: Format waktu pemilihan
    public function getWaktuPemilihanFormatted()
    {
        return $this->waktu_pemilihan->format('d F Y, H:i:s');
    }

    // Helper: Get nama kandidat yang dipilih
    public function getNamaKandidatDipilih()
    {
        return $this->kandidat ? $this->kandidat->nama_ketua : '-';
    }
}