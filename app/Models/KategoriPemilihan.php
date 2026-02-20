<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan

class KategoriPemilihan extends Model
{
    use SoftDeletes; // Tambahkan

    protected $table = 'tb_kategori_pemilihan';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'id_event',
        'nama_kategori',
        'jenis',
        'deskripsi'
    ];

    // Relasi dengan event
    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event', 'id_event');
    }

    // Relasi dengan Kandidat
    public function kandidat()
    {
        return $this->hasMany(Kandidat::class, 'id_kategori', 'id_kategori');
    }

    // Relasi dengan Pemilihan
    public function pemilihan()
    {
        return $this->hasMany(Pemilihan::class, 'id_kategori', 'id_kategori');
    }
}
