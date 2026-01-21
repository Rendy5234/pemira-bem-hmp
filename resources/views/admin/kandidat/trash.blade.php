@extends('admin.layouts.app')

@section('title', 'Trash Kandidat')

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
</style>

<div class="page-header">
    <h1>Trash Kandidat</h1>
    <a href="{{ route('admin.kandidat.selectEvent') }}" class="btn-primary">Kembali</a>
</div>

@if($kandidats->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No. Urut</th>
                <th>Pasangan</th>
                <th>Event & Kategori</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kandidats as $kandidat)
            <tr>
                <td style="text-align: center;"><strong style="font-size: 18px; color: #6b7280;">{{ $kandidat->nomor_urut }}</strong></td>
                <td>
                    <strong>Ketua:</strong> {{ $kandidat->nama_ketua }}<br>
                    <small style="color: #6b7280;">{{ $kandidat->nim_ketua }}</small>
                    <br><br>
                    <strong>Wakil:</strong> {{ $kandidat->nama_wakil }}<br>
                    <small style="color: #6b7280;">{{ $kandidat->nim_wakil }}</small>
                </td>
                <td>
                    @if($kandidat->kategoriPemilihan && $kandidat->kategoriPemilihan->event)
                        <strong>{{ $kandidat->kategoriPemilihan->event->nama_event }}</strong><br>
                        <small style="color: #6b7280;">{{ $kandidat->kategoriPemilihan->event->periode }}</small>
                        <br><br>
                        {{ $kandidat->kategoriPemilihan->nama_kategori }} 
                        <span class="badge badge-{{ strtolower($kandidat->kategoriPemilihan->jenis) }}">{{ $kandidat->kategoriPemilihan->jenis }}</span>
                    @elseif($kandidat->kategoriPemilihan)
                        {{ $kandidat->kategoriPemilihan->nama_kategori }} 
                        <span class="badge badge-{{ strtolower($kandidat->kategoriPemilihan->jenis) }}">{{ $kandidat->kategoriPemilihan->jenis }}</span>
                        <br><small style="color: #9ca3af;">(Event sudah dihapus)</small>
                    @else
                        <span style="color: #9ca3af;">Kategori & Event sudah dihapus</span>
                    @endif
                </td>
                <td>{{ $kandidat->deleted_at->format('d F Y, H:i') }} WIB</td>
                <td>
                    <form action="{{ route('admin.kandidat.restore', $kandidat->id_kandidat) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-action btn-success" onclick="return confirm('Restore kandidat ini?')">Restore</button>
                    </form>
                    
                    <form action="{{ route('admin.kandidat.forceDelete', $kandidat->id_kandidat) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-danger" onclick="return confirm('Hapus PERMANEN? Data tidak bisa dikembalikan!')">Hapus Permanen</button>
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
        <p>Tidak ada kandidat yang dihapus</p>
    </div>
</div>
@endif
@endsection