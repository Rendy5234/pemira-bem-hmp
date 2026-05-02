@extends('admin.layouts.app')

@section('title', 'Laporan - ' . $event->nama_event)

@section('content')
<style>
    .page-header {
        margin-bottom: 24px;
    }
    .breadcrumb {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 6px;
    }
    .breadcrumb a { color: #2563eb; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }
    .page-header h1 {
        font-size: 24px;
        color: #333;
        margin-bottom: 4px;
    }
    .page-header p { color: #6b7280; font-size: 13px; }
    .header-actions {
        display: flex;
        gap: 10px;
        margin-top: 12px;
    }
    .btn-primary {
        background-color: #2563eb;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        font-size: 13px;
    }
    .btn-primary:hover { background-color: #1d4ed8; }
    .btn-secondary {
        background-color: #6b7280;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        font-size: 13px;
    }
    .btn-secondary:hover { background-color: #4b5563; }
    .btn-print {
        background-color: #059669;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        font-size: 13px;
        cursor: pointer;
        border: none;
    }
    .btn-print:hover { background-color: #047857; }

    /* Filter */
    .filter-section {
        background: white;
        padding: 14px 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }
    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
        margin-bottom: 10px;
    }
    .filter-grid-row2 {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }
    .filter-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 4px;
        color: #374151;
        font-size: 12px;
    }
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 13px;
        box-sizing: border-box;
    }
    .btn-reset {
        background-color: #f59e0b;
        color: white;
        padding: 7px 14px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        height: 34px;
    }
    .btn-reset:hover { background-color: #d97706; }

    /* Info event */
    .event-info {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    .info-item label {
        display: block;
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 4px;
    }
    .info-item span {
        font-size: 14px;
        color: #111827;
        font-weight: 500;
    }
    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-draft   { background-color: #fef3c7; color: #92400e; }
    .badge-aktif   { background-color: #d1fae5; color: #065f46; }
    .badge-selesai { background-color: #e5e7eb; color: #374151; }
    .badge-bem     { background-color: #dbeafe; color: #1e40af; }
    .badge-hmp     { background-color: #ede9fe; color: #6d28d9; }

    /* Rekap per kategori */
    .kategori-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .kategori-header {
        background: #f9fafb;
        padding: 14px 16px;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .kategori-header h3 {
        font-size: 16px;
        color: #111827;
        margin: 0;
    }
    .kategori-header .meta {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        background-color: #f9fafb;
        padding: 10px 14px;
        text-align: left;
        font-weight: bold;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
        font-size: 12px;
    }
    td {
        padding: 10px 14px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        font-size: 13px;
    }
    .pemenang-row {
        background-color: #f0fdf4 !important;
    }
    .pemenang-row td { font-weight: 600; }
    .progress-bar-wrap {
        background: #e5e7eb;
        border-radius: 4px;
        height: 8px;
        width: 100%;
        min-width: 80px;
    }
    .progress-bar-fill {
        background: #2563eb;
        height: 8px;
        border-radius: 4px;
    }
    .no-kandidat {
        padding: 24px;
        text-align: center;
        color: #9ca3af;
        font-size: 13px;
    }
    .no-data {
        text-align: center;
        padding: 60px;
        color: #9ca3af;
    }
</style>

<div class="page-header">
    <div class="breadcrumb">
        <a href="{{ route('admin.laporan.index') }}">Laporan</a> /
        {{ $event->nama_event }}
    </div>
    <h1>{{ $event->nama_event }}</h1>
    <p>Laporan hasil pemilihan — Periode {{ $event->periode }}</p>
    <div class="header-actions">
        <a href="{{ route('admin.laporan.index') }}" class="btn-secondary">← Kembali</a>
        <a href="{{ route('admin.laporan.print', $event->id_event) }}" target="_blank" class="btn-print">🖨️ Cetak / Download PDF</a>
    </div>
</div>

{{-- Info Event --}}
<div class="event-info">
    <div class="info-item">
        <label>Periode</label>
        <span>{{ $event->periode }}</span>
    </div>
    <div class="info-item">
        <label>Tanggal Pemilihan</label>
        <span>{{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') }} — {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d M Y') }}</span>
    </div>
    <div class="info-item">
        <label>Total Partisipan</label>
        <span>{{ $totalPemilih }} pemilih</span>
    </div>
    <div class="info-item">
        <label>Status</label>
        <span><span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span></span>
    </div>
</div>

{{-- Filter --}}
<div class="filter-section">
    <form action="{{ route('admin.laporan.detail', $event->id_event) }}" method="GET" id="filterForm">
        <div class="filter-grid">
            <div class="filter-group">
                <label>🏢 Jenis</label>
                <select name="jenis" onchange="this.form.submit()">
                    <option value="">Semua (BEM & HMP)</option>
                    <option value="BEM" {{ request('jenis') == 'BEM' ? 'selected' : '' }}>BEM</option>
                    <option value="HMP" {{ request('jenis') == 'HMP' ? 'selected' : '' }}>HMP</option>
                </select>
            </div>
            <div class="filter-group">
                <label>🏛️ Fakultas</label>
                <select name="fakultas" onchange="this.form.submit()">
                    <option value="">Semua Fakultas</option>
                    @foreach($fakultasList as $f)
                        <option value="{{ $f }}" {{ request('fakultas') == $f ? 'selected' : '' }}>{{ $f }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>📚 Prodi</label>
                <select name="prodi" onchange="this.form.submit()">
                    <option value="">Semua Prodi</option>
                    @foreach($prodiList as $p)
                        <option value="{{ $p }}" {{ request('prodi') == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="filter-grid-row2">
            <div class="filter-group">
                <label>📅 Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" onchange="this.form.submit()">
            </div>
            <div class="filter-group">
                <label>📅 Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" onchange="this.form.submit()">
            </div>
            <div>
                <a href="{{ route('admin.laporan.detail', $event->id_event) }}" class="btn-reset">🔄 Reset</a>
            </div>
        </div>
    </form>
</div>

{{-- Rekap per Kategori --}}
@if(count($rekapData) > 0)
    @foreach($rekapData as $rekap)
    <div class="kategori-card">
        <div class="kategori-header">
            <div>
                <h3>{{ $rekap['kategori']->nama_kategori }}
                    <span class="badge badge-{{ strtolower($rekap['kategori']->jenis) }}">
                        {{ $rekap['kategori']->jenis }}
                    </span>
                </h3>
                <div class="meta">Total suara masuk: <strong>{{ $rekap['total_suara'] }}</strong></div>
            </div>
        </div>

        @if($rekap['kandidats']->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">No.</th>
                    <th>Pasangan Calon</th>
                    <th style="width:110px;">Suara</th>
                    <th style="width:200px;">Persentase</th>
                    <th style="width:80px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $maxSuara = $rekap['kandidats']->max('jumlah_suara');
                @endphp
                @foreach($rekap['kandidats'] as $item)
                @php
                    $isPemenang = $rekap['total_suara'] > 0 && $item['jumlah_suara'] == $maxSuara && $maxSuara > 0;
                @endphp
                <tr {{ $isPemenang ? 'class=pemenang-row' : '' }}>
                    <td style="text-align:center;font-weight:bold;">{{ $item['kandidat']->nomor_urut }}</td>
                    <td>
                        <strong>{{ $item['kandidat']->nama_ketua }}</strong>
                        <div style="font-size:11px;color:#6b7280;">NIM: {{ $item['kandidat']->nim_ketua }}</div>
                        @if($item['kandidat']->nama_wakil)
                        <div style="font-size:12px;color:#374151;margin-top:2px;">
                            Wakil: {{ $item['kandidat']->nama_wakil }}
                            <span style="color:#9ca3af;">({{ $item['kandidat']->nim_wakil }})</span>
                        </div>
                        @endif
                    </td>
                    <td style="font-size:20px;font-weight:bold;color:#111827;">
                        {{ $item['jumlah_suara'] }}
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="progress-bar-wrap" style="flex:1;">
                                <div class="progress-bar-fill" style="width:{{ $item['persentase'] }}%;background-color:{{ $isPemenang ? '#059669' : '#2563eb' }};"></div>
                            </div>
                            <span style="font-weight:bold;min-width:40px;text-align:right;">{{ $item['persentase'] }}%</span>
                        </div>
                    </td>
                    <td>
                        @if($isPemenang && $rekap['total_suara'] > 0)
                            <span style="color:#059669;font-weight:bold;font-size:12px;">🏆 Unggul</span>
                        @else
                            <span style="color:#6b7280;font-size:12px;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-kandidat">Belum ada kandidat di kategori ini.</div>
        @endif
    </div>
    @endforeach
@else
<div class="no-data">
    <p style="font-size:16px;margin-bottom:8px;">Belum ada kategori pemilihan</p>
    <p>Tambahkan kategori di menu Event terlebih dahulu</p>
</div>
@endif
@endsection