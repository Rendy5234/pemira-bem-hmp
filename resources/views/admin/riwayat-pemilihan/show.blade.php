@extends('admin.layouts.app')

@section('title', 'Detail Pemilihan')

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

        .detail-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 30px;
            margin-bottom: 20px;
        }

        .detail-section {
            margin-bottom: 30px;
        }

        .detail-section:last-child {
            margin-bottom: 0;
        }

        .detail-section h2 {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            width: 200px;
            font-weight: bold;
            color: #374151;
        }

        .detail-value {
            flex: 1;
            color: #6b7280;
        }

        .kandidat-box {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-top: 10px;
        }

        .nomor-urut-big {
            font-size: 48px;
            font-weight: bold;
            color: #2563eb;
            text-align: center;
            margin-bottom: 10px;
        }

        .kandidat-nama {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            text-align: center;
            margin-bottom: 5px;
        }

        .kandidat-nim {
            text-align: center;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .foto-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }

        .foto-item {
            text-align: center;
        }

        .foto-item img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            margin-bottom: 10px;
        }

        .foto-item p {
            font-weight: bold;
            color: #374151;
        }

        .timestamp-box {
            background: #dbeafe;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }

        .timestamp-box strong {
            font-size: 24px;
            color: #1e40af;
            display: block;
            margin-bottom: 5px;
        }

        .timestamp-box p {
            color: #1e40af;
            margin: 0;
        }
    </style>

    <div class="page-header">
        <h1>📊 Detail Data Pemilihan</h1>
        <a href="{{ route('admin.riwayat-pemilihan.index', [$event->id_event, $kategori->id_kategori]) }}"
            class="btn-back">← Kembali</a>
    </div>

    <div class="detail-card">
        <!-- EVENT & KATEGORI INFO -->
        <div class="detail-section">
            <h2>📋 Informasi Event & Kategori</h2>
            <div class="detail-row">
                <div class="detail-label">Nama Event:</div>
                <div class="detail-value"><strong>{{ $event->nama_event }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Periode:</div>
                <div class="detail-value">{{ $event->periode }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Kategori Pemilihan:</div>
                <div class="detail-value"><strong>{{ $kategori->nama_kategori }}</strong> ({{ $kategori->jenis }})</div>
            </div>
        </div>

        <!-- WAKTU PEMILIHAN -->
        <div class="detail-section">
            <h2>⏰ Waktu Pemilihan</h2>
            <div class="timestamp-box">
                <strong>{{ $pemilihan->waktu_pemilihan->format('d F Y') }}</strong>
                <p>Pukul {{ $pemilihan->waktu_pemilihan->format('H:i:s') }} WIB</p>
            </div>
        </div>

        <!-- DATA PEMILIH -->
        <div class="detail-section">
            <h2>👤 Data Pemilih</h2>
            <div class="detail-row">
                <div class="detail-label">NIM:</div>
                <div class="detail-value"><strong>{{ $pemilihan->nim }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Nama:</div>
                <div class="detail-value"><strong>{{ $pemilihan->nama_pemilih }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">IP Address:</div>
                <div class="detail-value">{{ $pemilihan->ip_address ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">User Agent:</div>
                <div class="detail-value" style="font-size: 12px;">{{ $pemilihan->user_agent ?? '-' }}</div>
            </div>
        </div>

        <!-- KANDIDAT YANG DIPILIH -->
        <div class="detail-section">
            <h2>🗳️ Kandidat yang Dipilih</h2>
            @if($pemilihan->kandidat)
                <div class="kandidat-box">
                    <div class="nomor-urut-big">{{ $pemilihan->kandidat->nomor_urut }}</div>

                    <div class="kandidat-nama">{{ $pemilihan->kandidat->nama_ketua }}</div>
                    <div class="kandidat-nim">NIM: {{ $pemilihan->kandidat->nim_ketua }}</div>

                    @if($pemilihan->kandidat->nama_wakil)
                        <div style="text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                            <div class="kandidat-nama" style="font-size: 18px;">{{ $pemilihan->kandidat->nama_wakil }}</div>
                            <div class="kandidat-nim">NIM: {{ $pemilihan->kandidat->nim_wakil }}</div>
                        </div>
                    @endif

                    <!-- Foto Kandidat -->
                    <div class="foto-box">
                        @if($pemilihan->kandidat->foto_ketua)
                            <div class="foto-item">
                                <img src="{{ asset('storage/' . $pemilihan->kandidat->foto_ketua) }}" alt="Foto Ketua">
                                <p>Ketua</p>
                            </div>
                        @endif

                        @if($pemilihan->kandidat->foto_wakil)
                            <div class="foto-item">
                                <img src="{{ asset('storage/' . $pemilihan->kandidat->foto_wakil) }}" alt="Foto Wakil">
                                <p>Wakil</p>
                            </div>
                        @endif
                    </div>

                    @if($pemilihan->kandidat->visi)
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                            <strong style="color: #374151;">Visi:</strong>
                            <p style="color: #6b7280; margin-top: 5px;">{{ $pemilihan->kandidat->visi }}</p>
                        </div>
                    @endif

                    @if($pemilihan->kandidat->misi)
                        <div style="margin-top: 15px;">
                            <strong style="color: #374151;">Misi:</strong>
                            <p style="color: #6b7280; margin-top: 5px; white-space: pre-line;">{{ $pemilihan->kandidat->misi }}</p>
                        </div>
                    @endif
                </div>
            @else
                <p style="color: #9ca3af;">Data kandidat tidak tersedia</p>
            @endif
        </div>

        <!-- METADATA -->
        <div class="detail-section">
            <h2>📝 Metadata</h2>
            <div class="detail-row">
                <div class="detail-label">ID Pemilihan:</div>
                <div class="detail-value">{{ $pemilihan->id_pemilihan }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Dibuat:</div>
                <div class="detail-value">{{ $pemilihan->created_at->format('d F Y, H:i:s') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Terakhir Update:</div>
                <div class="detail-value">{{ $pemilihan->updated_at->format('d F Y, H:i:s') }}</div>
            </div>
        </div>
    </div>

@endsection