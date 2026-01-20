@extends('admin.layouts.app')

@section('title', 'Detail Kandidat')

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
    .badge-bem { background-color: #dbeafe; color: #1e40af; }
    .badge-hmp { background-color: #fce7f3; color: #9f1239; }
    .foto-kandidat {
        max-width: 200px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
</style>

<div class="page-header">
    <div>
        <h1>Detail Kandidat #{{ $kandidat->nomor_urut }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.kandidat.selectEvent') }}">Pilih Event</a> / 
            <a href="{{ route('admin.kandidat.selectKategori', $event->id_event) }}">{{ $event->nama_event }}</a> / 
            <a href="{{ route('admin.kandidat.index', [$event->id_event, $kategori->id_kategori]) }}">{{ $kategori->nama_kategori }}</a> / 
            Detail
        </div>
    </div>
    <div class="btn-group">
        @if($admin->isSuperAdmin() || $admin->isAdmin())
        <a href="{{ route('admin.kandidat.edit', [$event->id_event, $kategori->id_kategori, $kandidat->id_kandidat]) }}" class="btn btn-primary">Edit</a>
        @endif
        <a href="{{ route('admin.kandidat.index', [$event->id_event, $kategori->id_kategori]) }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<!-- Event Info -->
<div class="info-card">
    <h2>Informasi Event</h2>
    <table class="info-table">
        <tr>
            <td>Event</td>
            <td><strong>{{ $event->nama_event }}</strong></td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>{{ $event->periode }}</td>
        </tr>
        <tr>
            <td>Kategori</td>
            <td>{{ $kategori->nama_kategori }} <span class="badge badge-{{ strtolower($kategori->jenis) }}">{{ $kategori->jenis }}</span></td>
        </tr>
        <tr>
            <td>Nomor Urut</td>
            <td><strong style="font-size: 24px; color: #2563eb;">{{ $kandidat->nomor_urut }}</strong></td>
        </tr>
    </table>
</div>

<!-- Data Ketua -->
<div class="info-card">
    <h2>Data Ketua</h2>
    <table class="info-table">
        <tr>
            <td>Nama Ketua</td>
            <td><strong>{{ $kandidat->nama_ketua }}</strong></td>
        </tr>
        <tr>
            <td>NIM Ketua</td>
            <td>{{ $kandidat->nim_ketua }}</td>
        </tr>
        <tr>
            <td>Foto Ketua</td>
            <td>
                @if($kandidat->foto_ketua)
                <img src="{{ asset('storage/' . $kandidat->foto_ketua) }}" alt="Foto Ketua" class="foto-kandidat">
                @else
                <span style="color: #9ca3af;">Tidak ada foto</span>
                @endif
            </td>
        </tr>
    </table>
</div>

<!-- Data Wakil -->
<div class="info-card">
    <h2>Data Wakil</h2>
    <table class="info-table">
        <tr>
            <td>Nama Wakil</td>
            <td><strong>{{ $kandidat->nama_wakil }}</strong></td>
        </tr>
        <tr>
            <td>NIM Wakil</td>
            <td>{{ $kandidat->nim_wakil }}</td>
        </tr>
        <tr>
            <td>Foto Wakil</td>
            <td>
                @if($kandidat->foto_wakil)
                <img src="{{ asset('storage/' . $kandidat->foto_wakil) }}" alt="Foto Wakil" class="foto-kandidat">
                @else
                <span style="color: #9ca3af;">Tidak ada foto</span>
                @endif
            </td>
        </tr>
    </table>
</div>

<!-- Visi Misi -->
<div class="info-card">
    <h2>Visi</h2>
    <p style="color: #374151; line-height: 1.6; white-space: pre-wrap;">{{ $kandidat->visi }}</p>
</div>

<div class="info-card">
    <h2>Misi</h2>
    <p style="color: #374151; line-height: 1.6; white-space: pre-wrap;">{{ $kandidat->misi }}</p>
</div>

<!-- Timestamps -->
<div class="info-card">
    <h2>Informasi Tambahan</h2>
    <table class="info-table">
        <tr>
            <td>Dibuat</td>
            <td>{{ $kandidat->created_at->format('d F Y, H:i') }} WIB</td>
        </tr>
        @if($kandidat->updated_at != $kandidat->created_at)
        <tr>
            <td>Terakhir Diupdate</td>
            <td>{{ $kandidat->updated_at->format('d F Y, H:i') }} WIB</td>
        </tr>
        @endif
    </table>
</div>
@endsection