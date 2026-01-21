@extends('admin.layouts.app')

@section('title', 'Kelola Kandidat')

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
    .header-actions {
        display: flex;
        gap: 10px;
    }
    .btn-primary {
        background-color: #2563eb;
        color: white;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
    }
    .btn-primary:hover {
        background-color: #1d4ed8;
    }
    .btn-secondary {
        background-color: #6b7280;
        color: white;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
    }
    .btn-secondary:hover {
        background-color: #4b5563;
    }
    .info-box {
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        margin-bottom: 20px;
    }
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-draft { background-color: #fef3c7; color: #92400e; }
    .badge-aktif { background-color: #d1fae5; color: #065f46; }
    .badge-selesai { background-color: #e5e7eb; color: #374151; }
    .badge-bem { background-color: #dbeafe; color: #1e40af; }
    .badge-hmp { background-color: #fce7f3; color: #9f1239; }
    .table-container {
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        background-color: #f9fafb;
        padding: 15px;
        text-align: left;
        font-weight: bold;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }
    td {
        padding: 15px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
    }
    tr:hover {
        background-color: #f9fafb;
    }
    .btn-action {
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 12px;
        margin-right: 5px;
        display: inline-block;
    }
    .btn-view {
        background-color: #3b82f6;
        color: white;
    }
    .btn-view:hover {
        background-color: #2563eb;
    }
    .btn-edit {
        background-color: #fbbf24;
        color: white;
    }
    .btn-edit:hover {
        background-color: #f59e0b;
    }
    .btn-delete {
        background-color: #ef4444;
        color: white;
        border: none;
        cursor: pointer;
    }
    .btn-delete:hover {
        background-color: #dc2626;
    }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #9ca3af;
    }
</style>

<div class="page-header">
    <div>
        <h1>Kelola Kandidat</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.kandidat.selectEvent') }}">Pilih Event</a> / 
            <a href="{{ route('admin.kandidat.selectKategori', $event->id_event) }}">{{ $event->nama_event }}</a> / 
            {{ $kategori->nama_kategori }}
        </div>
    </div>
    <a href="{{ route('admin.kandidat.selectKategori', $event->id_event) }}" class="btn-secondary">Kembali</a>
</div>

<div class="info-box">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0 0 10px 0; font-size: 20px;">{{ $event->nama_event }}</h3>
            <div style="color: #6b7280; font-size: 14px; margin-bottom: 10px;">
                Periode: {{ $event->periode }} | {{ $event->getTanggalMulaiFormatted() }} - {{ $event->getTanggalSelesaiFormatted() }}
            </div>
            <span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
            <span class="badge badge-{{ strtolower($kategori->jenis) }}">{{ $kategori->jenis }}</span>
        </div>
        @if($admin->isSuperAdmin() || $admin->isAdmin())
        <a href="{{ route('admin.kandidat.create', [$event->id_event, $kategori->id_kategori]) }}" class="btn-primary">+ Tambah Kandidat</a>
        @endif
    </div>
</div>

@if($kandidats->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th style="width: 80px;">No. Urut</th>
                <th>Ketua</th>
                <th>Wakil</th>
                <th>Visi</th>
                <th style="width: 200px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kandidats as $kandidat)
            <tr>
                <td style="text-align: center;"><strong style="font-size: 18px; color: #2563eb;">{{ $kandidat->nomor_urut }}</strong></td>
                <td>
                    <strong>{{ $kandidat->nama_ketua }}</strong><br>
                    <small style="color: #6b7280;">NIM: {{ $kandidat->nim_ketua }}</small>
                </td>
                <td>
                    <strong>{{ $kandidat->nama_wakil }}</strong><br>
                    <small style="color: #6b7280;">NIM: {{ $kandidat->nim_wakil }}</small>
                </td>
                <td>{{ Str::limit($kandidat->visi, 60) }}</td>
                <td>
                    <a href="{{ route('admin.kandidat.show', [$event->id_event, $kategori->id_kategori, $kandidat->id_kandidat]) }}" class="btn-action btn-view">Detail</a>
                    
                    @if($admin->isSuperAdmin() || $admin->isAdmin())
                    <a href="{{ route('admin.kandidat.edit', [$event->id_event, $kategori->id_kategori, $kandidat->id_kandidat]) }}" class="btn-action btn-edit">Edit</a>
                    @endif
                    
                    @if($admin->isSuperAdmin())
                    <form action="{{ route('admin.kandidat.destroy', [$event->id_event, $kategori->id_kategori, $kandidat->id_kandidat]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus kandidat ini?')">Hapus</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="table-container">
    <div class="empty-state">
        <p style="font-size: 18px; margin-bottom: 10px;">Belum ada kandidat</p>
        <p>Klik tombol "Tambah Kandidat" untuk mulai menambahkan kandidat</p>
    </div>
</div>
@endif
@endsection