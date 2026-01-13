@extends('admin.layouts.app')

@section('title', 'Kelola Event')

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

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-draft {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-aktif {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-selesai {
            background-color: #e5e7eb;
            color: #374151;
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

        .kategori-count {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
    </style>

    <div class="page-header">
        <h1>Kelola Event Pemilihan</h1>
        <a href="{{ route('admin.event.create') }}" class="btn-primary">+ Tambah Event</a>
    </div>

    @if ($events->count() > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Event</th>
                        <th>Periode</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Waktu</th>
                        <th>Kategori Pemilihan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $index => $event)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $event->nama_event }}</strong>
                                @if ($event->deskripsi)
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 3px;">
                                        {{ Str::limit($event->deskripsi, 50) }}</div>
                                @endif
                            </td>
                            <td><strong>{{ $event->periode }}</strong></td>
                            <td>
                                {{ $event->getTanggalMulaiFormatted() }}<br>
                                <small style="color: #9ca3af;">s/d {{ $event->getTanggalSelesaiFormatted() }}</small>
                            </td>
                            <td>{{ $event->waktu_mulai }} - {{ $event->waktu_selesai }}</td>
                            <td>
                                <strong>{{ $event->kategori_pemilihan_count }} Kategori</strong>
                                <div class="kategori-count">
                                    @php
                                        $kategoriBEM = $event->kategoriPemilihan->where('jenis', 'BEM')->count();
                                        $kategoriHMP = $event->kategoriPemilihan->where('jenis', 'HMP')->count();
                                    @endphp
                                    BEM: {{ $kategoriBEM }}, HMP: {{ $kategoriHMP }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $event->status }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.event.show', $event->id_event) }}"
                                    class="btn-action btn-view">Detail</a>
                                <a href="{{ route('admin.event.edit', $event->id_event) }}"
                                    class="btn-action btn-edit">Edit</a>

                                @if ($admin->isSuperAdmin())
                                    <form action="{{ route('admin.event.destroy', $event->id_event) }}" method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('Yakin ingin menghapus event ini? Semua kategori pemilihan akan ikut terhapus!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete">Hapus</button>
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
                <p style="font-size: 18px; margin-bottom: 10px;">Belum ada event</p>
                <p>Klik tombol "Tambah Event" untuk membuat event pemilihan baru</p>
            </div>
        </div>
    @endif
@endsection
