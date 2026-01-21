@extends('admin.layouts.app')

@section('title', 'Trash Kategori Pemilihan')

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
    .header-actions {
        display: flex;
        gap: 10px;
    }
    .btn {
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        display: inline-block;
    }
    .btn-primary {
        background-color: #2563eb;
        color: white;
    }
    .btn-primary:hover {
        background-color: #1d4ed8;
    }
    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #4b5563;
    }
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
        border: none;
        cursor: pointer;
    }
    .btn-success {
        background-color: #10b981;
        color: white;
    }
    .btn-success:hover {
        background-color: #059669;
    }
    .btn-danger {
        background-color: #ef4444;
        color: white;
    }
    .btn-danger:hover {
        background-color: #dc2626;
    }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #9ca3af;
    }
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-bem { background-color: #dbeafe; color: #1e40af; }
    .badge-hmp { background-color: #fce7f3; color: #9f1239; }
    .info-text {
        color: #6b7280;
        font-size: 12px;
        margin-top: 3px;
    }
</style>

<div class="page-header">
    <h1>🗑️ Trash Kategori Pemilihan</h1>
    <div class="header-actions">
        <a href="{{ route('admin.event.trash') }}" class="btn btn-secondary">← Trash Event</a>
        <a href="{{ route('admin.event.index') }}" class="btn btn-primary">Kembali ke Event</a>
    </div>
</div>

@if($kategoris->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Jenis</th>
                <th>Event</th>
                <th>Deskripsi</th>
                <th>Dihapus Pada</th>
                <th style="width: 200px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategoris as $index => $kategori)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                <td>
                    <span class="badge badge-{{ strtolower($kategori->jenis) }}">
                        {{ $kategori->jenis }}
                    </span>
                </td>
                <td>
                    @if($kategori->event)
                        <strong>{{ $kategori->event->nama_event }}</strong>
                        <div class="info-text">{{ $kategori->event->periode }}</div>
                        @if($kategori->event->deleted_at)
                        <div class="info-text" style="color: #ef4444;">Event juga terhapus</div>
                        @endif
                    @else
                        <span style="color: #9ca3af;">Event sudah dihapus permanen</span>
                    @endif
                </td>
                <td>{{ $kategori->deskripsi ?? '-' }}</td>
                <td>{{ $kategori->deleted_at->format('d F Y, H:i') }} WIB</td>
                <td>
                    <form action="{{ route('admin.event.restoreKategori', $kategori->id_kategori) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-action btn-success" onclick="return confirm('Restore kategori ini?')">Restore</button>
                    </form>
                    
                    <form action="{{ route('admin.event.forceDeleteKategori', $kategori->id_kategori) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-danger" onclick="return confirm('⚠️ PERINGATAN!\n\nHapus PERMANEN kategori ini?\n\nKandidat yang terkait akan hilang permanen!\n\nData tidak bisa dikembalikan!')">Hapus Permanen</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="table-container">
    <div class="empty-state">
        <p style="font-size: 18px; margin-bottom: 10px;">Trash kategori kosong</p>
        <p>Tidak ada kategori pemilihan yang dihapus</p>
    </div>
</div>
@endif

<div style="margin-top: 20px; padding: 15px; background-color: #fef3c7; border: 1px solid #fcd34d; border-radius: 8px; color: #92400e;">
    <strong>ℹ️ Informasi:</strong> Kategori yang dihapus permanen akan menghilangkan semua data kandidat yang terkait.
</div>
@endsection