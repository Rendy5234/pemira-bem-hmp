<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kandidat extends Model
{
    use SoftDeletes;

    protected $table = 'tb_kandidat';
    protected $primaryKey = 'id_kandidat';

    protected $fillable = [
        'id_kategori',
        'nomor_urut',
        'nama_ketua',
        'nim_ketua',
        'foto_ketua',
        'nama_wakil',
        'nim_wakil',
        'foto_wakil',
        'visi',
        'misi'
    ];

    // Relasi ke kategori pemilihan
    public function kategoriPemilihan()
    {
        return $this->belongsTo(KategoriPemilihan::class, 'id_kategori', 'id_kategori');
    }

    // Relasi ke pemilihan
    public function pemilihan()
    {
        return $this->hasMany(Pemilihan::class, 'id_kandidat', 'id_kandidat');
    }
}
