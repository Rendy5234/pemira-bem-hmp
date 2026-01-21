@extends('admin.layouts.app')

@section('title', 'Event Trash')

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
</style>

<div class="page-header">
    <h1>🗑️ Trash Event</h1>
    <div class="header-actions">
        <a href="{{ route('admin.event.trashKategori') }}" class="btn btn-secondary">Trash Kategori →</a>
        <a href="{{ route('admin.event.index') }}" class="btn btn-primary">Kembali</a>
    </div>
</div>

@if($events->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Event</th>
                <th>Periode</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $index => $event)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $event->nama_event }}</strong></td>
                <td>{{ $event->periode }}</td>
                <td>{{ $event->deleted_at->format('d F Y, H:i') }} WIB</td>
                <td>
                    <form action="{{ route('admin.event.restore', $event->id_event) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-action btn-success" onclick="return confirm('Restore event ini?')">Restore</button>
                    </form>
                    <form action="{{ route('admin.event.forceDelete', $event->id_event) }}" method="POST" style="display: inline;">
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
        <p>Tidak ada event yang dihapus</p>
    </div>
</div>
@endif
@endsection