@extends('admin.layouts.app')

@section('title', 'Detail Event')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .page-header h1 {
        font-size: 28px;
        color: #333;
    }
    .breadcrumb {
        color: #666;
        font-size: 14px;
        margin-top: 5px;
    }
    .breadcrumb a {
        color: #2563eb;
        text-decoration: none;
    }
    .btn-group {
        display: flex;
        gap: 10px;
    }
    .btn {
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }
    .btn-primary {
        background-color: #2563eb;
        color: white;
    }
    .btn-primary:hover {
        background-color: #1d4ed8;
    }
    .btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }
    .btn-secondary:hover {
        background-color: #d1d5db;
    }
    .info-card {
        background: white;
        padding: 30px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }
    .info-card h2 {
        font-size: 20px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e5e7eb;
        color: #333;
    }
    .info-table {
        width: 100%;
    }
    .info-table tr {
        border-bottom: 1px solid #f3f4f6;
    }
    .info-table td {
        padding: 12px 0;
    }
    .info-table td:first-child {
        color: #6b7280;
        width: 200px;
        font-weight: 600;
    }
    .info-table td:last-child {
        color: #374151;
    }
    .badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: bold;
    }
    .badge-draft { background-color: #fef3c7; color: #92400e; }
    .badge-aktif { background-color: #d1fae5; color: #065f46; }
    .badge-selesai { background-color: #e5e7eb; color: #374151; }
    .badge-bem { background-color: #dbeafe; color: #1e40af; }
    .badge-hmp { background-color: #fce7f3; color: #9f1239; }
    .kategori-list {
        list-style: none;
        padding: 0;
    }
    .kategori-item {
        background-color: #f9fafb;
        padding: 15px;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
        margin-bottom: 10px;
    }
    .kategori-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .kategori-name {
        font-weight: bold;
        font-size: 16px;
        color: #374151;
    }
    .kategori-desc {
        color: #6b7280;
        font-size: 14px;
    }
</style>

<div>
    <div class="page-header">
        <div>
            <h1>Detail Event</h1>
            <div class="breadcrumb">
                <a href="{{ route('admin.event.index') }}">Event</a> / Detail
            </div>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.event.edit', $event->id_event) }}" class="btn btn-primary">Edit Event</a>
            <a href="{{ route('admin.event.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <!-- Info Event -->
    <div class="info-card">
        <h2>Informasi Event</h2>
        <table class="info-table">
            <tr>
                <td>Nama Event</td>
                <td><strong>{{ $event->nama_event }}</strong></td>
            </tr>
            <tr>
                <td>Periode</td>
                <td><strong>{{ $event->periode }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal Pelaksanaan</td>
                <td>
                    {{ $event->getTanggalMulaiFormatted() }} s/d {{ $event->getTanggalSelesaiFormatted() }}
                </td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>{{ $event->waktu_mulai }} - {{ $event->waktu_selesai }} WIB</td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td>{{ $event->deskripsi ?? '-' }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <span class="badge badge-{{ $event->status }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Dibuat</td>
                <td>{{ $event->created_at->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Terakhir Diupdate</td>
                <td>{{ $event->updated_at->format('d F Y, H:i') }} WIB</td>
            </tr>
        </table>
    </div>

    <!-- Kategori Pemilihan -->
    <div class="info-card">
        <h2>Kategori Pemilihan ({{ $event->kategoriPemilihan->count() }})</h2>
        
        @if($event->kategoriPemilihan->count() > 0)
        <ul class="kategori-list">
            @foreach($event->kategoriPemilihan as $kategori)
            <li class="kategori-item">
                <div class="kategori-item-header">
                    <span class="kategori-name">{{ $kategori->nama_kategori }}</span>
                    <span class="badge badge-{{ strtolower($kategori->jenis) }}">
                        {{ $kategori->jenis }}
                    </span>
                </div>
                @if($kategori->deskripsi)
                <div class="kategori-desc">{{ $kategori->deskripsi }}</div>
                @endif
            </li>
            @endforeach
        </ul>
        @else
        <p style="color: #9ca3af; text-align: center; padding: 30px 0;">Belum ada kategori pemilihan</p>
        @endif
    </div>
</div>
@endsection