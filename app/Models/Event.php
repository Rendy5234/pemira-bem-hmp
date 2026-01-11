<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $table = 'tb_event';
    protected $primaryKey = 'id_event';
    
    protected $fillable = [
        'nama_event',
        'periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi dengan kategori pemilihan
    public function kategoriPemilihan()
    {
        return $this->hasMany(KategoriPemilihan::class, 'id_event', 'id_event');
    }

    // Helper methods
    public function isAktif()
    {
        return $this->status === 'aktif';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isSelesai()
    {
        return $this->status === 'selesai';
    }

    public function getTanggalMulaiFormatted()
    {
        return Carbon::parse($this->tanggal_mulai)->format('d F Y');
    }

    public function getTanggalSelesaiFormatted()
    {
        return Carbon::parse($this->tanggal_selesai)->format('d F Y');
    }
}