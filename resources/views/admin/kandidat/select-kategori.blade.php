@extends('admin.layouts.app')

@section('title', 'Pilih Kategori')

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
    .breadcrumb {
        color: #666;
        font-size: 14px;
    }
    .breadcrumb a {
        color: #2563eb;
        text-decoration: none;
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
        cursor: pointer;
    }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #9ca3af;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div class="page-header">
        <h1>Pilih Kategori Pemilihan</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.kandidat.selectEvent') }}">Pilih Event</a> / {{ $event->nama_event }}
        </div>
    </div>
    <a href="{{ route('admin.kandidat.selectEvent') }}" class="btn-secondary">Kembali</a>
</div>

<div class="info-box">
    <div>
        <h3 style="margin: 0 0 10px 0; font-size: 20px;">{{ $event->nama_event }}</h3>
        <div style="color: #6b7280; font-size: 14px; margin-bottom: 10px;">
            Periode: {{ $event->periode }} | {{ $event->getTanggalMulaiFormatted() }} - {{ $event->getTanggalSelesaiFormatted() }}
        </div>
        <span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
    </div>
</div>

@if($event->kategoriPemilihan->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Jenis</th>
                <th>Deskripsi</th>
                <th>Jumlah Kandidat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->kategoriPemilihan as $index => $kategori)
            <tr onclick="window.location='{{ route('admin.kandidat.index', [$event->id_event, $kategori->id_kategori]) }}'">
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                <td>
                    <span class="badge badge-{{ strtolower($kategori->jenis) }}">
                        {{ $kategori->jenis }}
                    </span>
                </td>
                <td>{{ $kategori->deskripsi ?? '-' }}</td>
                <td><strong>{{ $kategori->kandidat->count() }} Kandidat</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="table-container">
    <div class="empty-state">
        <p style="font-size: 18px; margin-bottom: 10px;">Belum ada kategori pemilihan</p>
        <p>Edit event untuk menambahkan kategori pemilihan</p>
    </div>
</div>
@endif
@endsection