@extends('admin.layouts.app')

@section('title', 'Pilih Event')

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
        cursor: pointer;
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
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #9ca3af;
    }
</style>

<div class="page-header">
    <h1>Pilih Event untuk Kelola Kandidat</h1>
    @if($admin->isSuperAdmin())
    <a href="{{ route('admin.kandidat.trash') }}" class="btn-secondary">Trash</a>
    @endif
</div>

@if($events->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Event</th>
                <th>Periode</th>
                <th>Tanggal Pelaksanaan</th>
                <th>Kategori</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $index => $event)
            <tr onclick="window.location='{{ route('admin.kandidat.selectKategori', $event->id_event) }}'">
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $event->nama_event }}</strong></td>
                <td>{{ $event->periode }}</td>
                <td>
                    {{ $event->getTanggalMulaiFormatted() }}<br>
                    <small style="color: #9ca3af;">s/d {{ $event->getTanggalSelesaiFormatted() }}</small>
                </td>
                <td><strong>{{ $event->kategori_pemilihan_count }} Kategori</strong></td>
                <td>
                    <span class="badge badge-{{ $event->status }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="table-container">
    <div class="empty-state">
        <p style="font-size: 18px; margin-bottom: 10px;">Belum ada event</p>
        <p>Buat event terlebih dahulu di menu Event</p>
    </div>
</div>
@endif
@endsection