<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan

class Admin extends Authenticatable
{
    use Notifiable, SoftDeletes; // Tambahkan SoftDeletes

    protected $table = 'tb_admin';
    protected $primaryKey = 'id_admin';
    
    protected $fillable = [
        'name_admin',
        'username',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Helper method untuk cek role
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isReadStaf()
    {
        return $this->role === 'readstaf';
    }
}