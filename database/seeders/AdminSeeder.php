<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        Admin::create([
            'name_admin' => 'Super Administrator',
            'username' => 'superadmin',
            'password' => Hash::make('super123'),
            'role' => 'superadmin'
        ]);

        // Admin BEM
        Admin::create([
            'name_admin' => 'AdminBiasa',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Read Staff
        Admin::create([
            'name_admin' => 'Staff Viewer',
            'username' => 'readstaf',
            'password' => Hash::make('read123'),
            'role' => 'readstaf'
        ]);
    }
}