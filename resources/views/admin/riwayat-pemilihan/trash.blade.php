@extends('admin.layouts.app')

@section('title', 'Trash - Riwayat Pemilihan')

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
    .btn-back {
        background-color: #6b7280;
        color: white;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
    }
    .btn-back:hover {
        background-color: #4b5563;
    }
    .info-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }
    .info-card h2 {
        font-size: 20px;
        color: #1f2937;
        margin-bottom: 10px;
    }
    .info-card p {
        color: #6b7280;
        margin-bottom: 5px;
    }
    .alert-warning {
        background: #fef3c7;
        border: 1px solid #fbbf24;
        color: #92400e;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
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
        background-color: #fef3c7;
        padding: 15px;
        text-align: left;
        font-weight: bold;
        color: #92400e;
        border-bottom: 2px solid #fbbf24;
    }
    td {
        padding: 15px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
    }
    tr:hover {
        background-color: #fffbeb;
    }
    .btn-action {
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 12px;
        margin-right: 5px;
        display: inline-block;
    }
    .btn-restore {
        background-color: #10b981;
        color: white;
        border: none;
        cursor: pointer;
    }
    .btn-restore:hover {
        background-color: #059669;
    }
    .btn-delete-permanent {
        background-color: #ef4444;
        color: white;
        border: none;
        cursor: pointer;
    }
    .btn-delete-permanent:hover {
        background-color: #dc2626;
    }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #9ca3af;
    }
    .deleted-badge {
        background: #fecaca;
        color: #991b1b;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
</style>

<div class="page-header">
    <h1>🗑️ Trash - Riwayat Pemilihan</h1>
    <a href="{{ route('admin.riwayat-pemilihan.index', [$event->id_event, $kategori->id_kategori]) }}" class="btn-back">← Kembali</a>
</div>

<!-- INFO CARD -->
<div class="info-card">
    <h2>{{ $event->nama_event }} - {{ $kategori->nama_kategori }}</h2>
    <p><strong>📅 Periode:</strong> {{ $event->periode }}</p>
    <p><strong>📋 Jenis:</strong> {{ $kategori->jenis }}</p>
</div>

<div class="alert-warning">
    ⚠️ <strong>Perhatian:</strong> Data di trash dapat dipulihkan atau dihapus permanen. Penghapusan permanen tidak dapat dibatalkan!
</div>

@if($pemilihans->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu Voting</th>
                <th>NIM</th>
                <th>Nama Pemilih</th>
                <th>Kandidat Dipilih</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemilihans as $index => $pemilihan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $pemilihan->waktu_pemilihan->format('d/m/Y') }}</strong><br>
                    <small style="color: #9ca3af;">{{ $pemilihan->waktu_pemilihan->format('H:i:s') }}</small>
                </td>
                <td><strong>{{ $pemilihan->nim }}</strong></td>
                <td>{{ $pemilihan->nama_pemilih }}</td>
                <td>
                    @if($pemilihan->kandidat)
                    <div>
                        <span style="font-size: 20px; font-weight: bold; color: #2563eb;">{{ $pemilihan->kandidat->nomor_urut }}</span>
                        <strong>{{ $pemilihan->kandidat->nama_ketua }}</strong>
                    </div>
                    @else
                    <span style="color: #9ca3af;">-</span>
                    @endif
                </td>
                <td>
                    <span class="deleted-badge">{{ $pemilihan->deleted_at->diffForHumans() }}</span><br>
                    <small style="color: #9ca3af;">{{ $pemilihan->deleted_at->format('d/m/Y H:i') }}</small>
                </td>
                <td>
                    <form action="{{ route('admin.riwayat-pemilihan.restore', [$event->id_event, $kategori->id_kategori, $pemilihan->id_pemilihan]) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-action btn-restore" onclick="return confirm('Yakin ingin memulihkan data ini?')">
                            ♻️ Pulihkan
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.riwayat-pemilihan.forceDelete', [$event->id_event, $kategori->id_kategori, $pemilihan->id_pemilihan]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete-permanent" onclick="return confirm('PERINGATAN: Data akan dihapus PERMANEN dan tidak dapat dipulihkan!\n\nYakin ingin melanjutkan?')">
                            ❌ Hapus Permanen
                        </button>
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
        <p style="font-size: 18px; margin-bottom: 10px;">Trash kosong</p>
        <p>Tidak ada data pemilihan yang dihapus</p>
    </div>
</div>
@endif

@endsection