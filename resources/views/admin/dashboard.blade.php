@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .page-header {
        margin-bottom: 30px;
    }
    .page-header h1 {
        font-size: 28px;
        color: #333;
        margin-bottom: 5px;
    }
    .page-header p {
        color: #666;
    }
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    .stat-card h3 {
        color: #666;
        font-size: 14px;
        margin-bottom: 10px;
    }
    .stat-card .number {
        font-size: 36px;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }
    .stat-card p {
        color: #999;
        font-size: 12px;
    }
    /* Info Cards */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    .info-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    .info-card h3 {
        font-size: 18px;
        margin-bottom: 15px;
        color: #333;
    }
    .info-table {
        width: 100%;
    }
    .info-table tr {
        border-bottom: 1px solid #f3f4f6;
    }
    .info-table td {
        padding: 10px 0;
    }
    .info-table td:first-child {
        color: #666;
    }
    .info-table td:last-child {
        font-weight: bold;
        text-align: right;
    }
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-superadmin {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .badge-admin {
        background-color: #dbeafe;
        color: #1e40af;
    }
    .badge-readstaf {
        background-color: #f3f4f6;
        color: #374151;
    }
    /* Quick Actions */
    .action-list {
        list-style: none;
    }
    .action-item {
        padding: 15px;
        margin-bottom: 10px;
        background-color: #f9fafb;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .action-item:hover {
        background-color: #f3f4f6;
        cursor: pointer;
    }
    .soon-badge {
        font-size: 10px;
        background-color: #e5e7eb;
        color: #666;
        padding: 2px 8px;
        border-radius: 10px;
    }
    .permissions-box {
        margin-top: 30px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    .permissions-box h3 {
        font-size: 18px;
        margin-bottom: 15px;
        color: #333;
    }
    .permissions-box ul {
        list-style: none;
        padding: 0;
        font-size: 14px;
        color: #666;
    }
    .permissions-box li {
        padding: 5px 0;
    }
</style>

<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang, {{ $admin->name_admin }}!</p>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Event</h3>
        <div class="number">0</div>
        <p>Belum ada event</p>
    </div>
    <div class="stat-card">
        <h3>Total Kandidat</h3>
        <div class="number">0</div>
        <p>Belum ada kandidat</p>
    </div>
    <div class="stat-card">
        <h3>Total Suara</h3>
        <div class="number">0</div>
        <p>Belum ada voting</p>
    </div>
    <div class="stat-card">
        <h3>Total Mahasiswa</h3>
        <div class="number">0</div>
        <p>Belum ada data</p>
    </div>
</div>

<!-- Info Cards -->
<div class="info-grid">
    <!-- Account Info -->
    <div class="info-card">
        <h3>Informasi Akun</h3>
        <table class="info-table">
            <tr>
                <td>Nama</td>
                <td>{{ $admin->name_admin }}</td>
            </tr>
            <tr>
                <td>Username</td>
                <td>{{ $admin->username }}</td>
            </tr>
            <tr>
                <td>Role</td>
                <td>
                    <span class="badge 
                        @if($admin->isSuperAdmin()) badge-superadmin
                        @elseif($admin->isAdmin()) badge-admin
                        @else badge-readstaf
                        @endif">
                        {{ ucfirst($admin->role) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Quick Actions -->
    <div class="info-card">
        <h3>Quick Actions</h3>
        <ul class="action-list">
            <li class="action-item">
                <span>Buat Event Baru</span>
                <span class="soon-badge">Soon</span>
            </li>
            <li class="action-item">
                <span>Tambah Kandidat</span>
                <span class="soon-badge">Soon</span>
            </li>
            <li class="action-item">
                <span>Lihat Laporan</span>
                <span class="soon-badge">Soon</span>
            </li>
        </ul>
    </div>
</div>

<!-- Role Permissions -->
<div class="permissions-box">
    <h3>Hak Akses Role Anda</h3>
    
    @if($admin->isSuperAdmin())
        <div>
            <p style="font-weight: bold; color: #991b1b; margin-bottom: 10px;">Super Admin - Full Access</p>
            <ul>
                <li>✓ Kelola semua admin</li>
                <li>✓ Kelola event, kandidat, dan mahasiswa</li>
                <li>✓ Lihat dan export semua laporan</li>
                <li>✓ Setting sistem</li>
            </ul>
        </div>
    @elseif($admin->isAdmin())
        <div>
            <p style="font-weight: bold; color: #1e40af; margin-bottom: 10px;">Admin - Limited Access</p>
            <ul>
                <li>✓ Kelola event dan kandidat</li>
                <li>✓ Lihat laporan</li>
                <li>✗ Tidak bisa kelola admin lain</li>
                <li>✗ Tidak bisa ubah setting sistem</li>
            </ul>
        </div>
    @else
        <div>
            <p style="font-weight: bold; color: #374151; margin-bottom: 10px;">Read Staff - View Only</p>
            <ul>
                <li>✓ Lihat data event dan kandidat</li>
                <li>✓ Lihat laporan</li>
                <li>✗ Tidak bisa tambah/edit/hapus data</li>
            </ul>
        </div>
    @endif
</div>
@endsection