@extends('admin.layouts.app')

@section('title', 'Pilih Kategori - Riwayat Pemilihan')

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

        .event-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-bottom: 30px;
        }

        .event-info h2 {
            font-size: 22px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .event-info p {
            color: #6b7280;
            margin-bottom: 5px;
        }

        .kategori-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .kategori-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .kategori-card:hover {
            border-color: #2563eb;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .kategori-card h3 {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .kategori-info {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .kategori-info strong {
            color: #374151;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }

        .badge-BEM {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-HMP {
            background-color: #fef3c7;
            color: #92400e;
        }

        .stats-box {
            background: #f3f4f6;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .stats-box strong {
            color: #2563eb;
            font-size: 20px;
        }

        .empty-state {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 60px 20px;
            text-align: center;
            color: #9ca3af;
        }
    </style>

    <div class="page-header">
        <h1>📊 Pilih Kategori Pemilihan</h1>
        <a href="{{ route('admin.riwayat-pemilihan.selectEvent') }}" class="btn-back">← Kembali</a>
    </div>

    <div class="event-info">
        <h2>{{ $event->nama_event }}</h2>
        <p><strong>📅 Periode:</strong> {{ $event->periode }}</p>
        <p><strong>🗓️ Tanggal:</strong> {{ $event->getTanggalMulaiFormatted() }} -
            {{ $event->getTanggalSelesaiFormatted() }}</p>
        <p><strong>⏰ Waktu:</strong> {{ $event->waktu_mulai }} - {{ $event->waktu_selesai }}</p>
    </div>

    @if($kategoris->count() > 0)
        <div class="kategori-grid">
            @foreach($kategoris as $kategori)
                <div class="kategori-card"
                    onclick="window.location.href='{{ route('admin.riwayat-pemilihan.index', [$event->id_event, $kategori->id_kategori]) }}'">
                    <h3>{{ $kategori->nama_kategori }}</h3>

                    <div class="kategori-info">
                        <strong>📋 Jenis:</strong>
                        <span class="badge badge-{{ $kategori->jenis }}">{{ $kategori->jenis }}</span>
                    </div>

                    @if($kategori->deskripsi)
                        <div class="kategori-info" style="margin-top: 8px;">
                            {{ Str::limit($kategori->deskripsi, 100) }}
                        </div>
                    @endif

                    <div class="stats-box">
                        <div class="kategori-info">
                            <strong>🗳️ Total Pemilih:</strong>
                            <strong style="color: #2563eb;">{{ $kategori->jumlah_pemilihan ?? 0 }}</strong> orang
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <p style="font-size: 18px; margin-bottom: 10px;">Belum ada kategori pemilihan</p>
            <p>Silakan tambahkan kategori pemilihan di event ini terlebih dahulu</p>
        </div>
    @endif

@endsection