<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemilihan - {{ $event->nama_event }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            background: white;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .header h2 {
            font-size: 14px;
            font-weight: normal;
            margin-bottom: 8px;
        }
        .header p { font-size: 11px; color: #444; }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px 0;
            font-size: 12px;
        }
        .info-table td:first-child {
            width: 150px;
            color: #444;
        }
        .info-table td:nth-child(2) {
            width: 10px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            background: #f0f0f0;
            padding: 6px 10px;
            border-left: 4px solid #000;
            margin: 16px 0 10px 0;
        }

        table.rekap {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.rekap th {
            background: #333;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 11px;
        }
        table.rekap td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        table.rekap tr:nth-child(even) td {
            background: #f9f9f9;
        }
        .pemenang td {
            font-weight: bold;
            background: #f0fdf4 !important;
        }

        .progress-bar {
            display: inline-block;
            width: 120px;
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            vertical-align: middle;
            margin-right: 6px;
        }
        .progress-fill {
            display: inline-block;
            height: 8px;
            background: #333;
            border-radius: 4px;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-box .line {
            border-bottom: 1px solid #000;
            margin: 50px 0 4px 0;
        }
        .signature-box p { font-size: 11px; }

        .summary-box {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 16px;
            background: #fafafa;
        }
        .summary-box p { font-size: 12px; margin-bottom: 4px; }

        @media print {
            body { padding: 10px; }
            .no-print { display: none; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>

{{-- Tombol cetak (tidak muncul saat print) --}}
<div class="no-print" style="margin-bottom:20px;display:flex;gap:10px;">
    <button onclick="window.print()" style="padding:8px 20px;background:#2563eb;color:white;border:none;border-radius:4px;cursor:pointer;font-size:13px;">🖨️ Cetak / Simpan PDF</button>
    <button onclick="window.close()" style="padding:8px 20px;background:#6b7280;color:white;border:none;border-radius:4px;cursor:pointer;font-size:13px;">✖ Tutup</button>
</div>

{{-- Header Laporan --}}
<div class="header">
    <h1>Laporan Hasil Pemilihan Raya</h1>
    <h2>{{ $event->nama_event }}</h2>
    <p>Periode {{ $event->periode }} &nbsp;|&nbsp;
       {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d F Y') }} s/d
       {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d F Y') }}
    </p>
</div>

{{-- Info Event --}}
<table class="info-table">
    <tr>
        <td>Nama Event</td><td>:</td><td><strong>{{ $event->nama_event }}</strong></td>
    </tr>
    <tr>
        <td>Periode</td><td>:</td><td>{{ $event->periode }}</td>
    </tr>
    <tr>
        <td>Tanggal Pemilihan</td><td>:</td>
        <td>{{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d F Y') }} —
            {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d F Y') }}</td>
    </tr>
    <tr>
        <td>Waktu Pemilihan</td><td>:</td>
        <td>{{ $event->waktu_mulai }} — {{ $event->waktu_selesai }}</td>
    </tr>
    <tr>
        <td>Total Partisipan</td><td>:</td><td><strong>{{ $totalPemilih }} pemilih</strong></td>
    </tr>
    <tr>
        <td>Dicetak oleh</td><td>:</td>
        <td>{{ $admin->name_admin }} ({{ ucfirst($admin->role) }}) — {{ now()->format('d F Y, H:i') }}</td>
    </tr>
</table>

{{-- Rekap per Kategori --}}
@foreach($rekapData as $rekap)
<div class="section-title">
    {{ $rekap['kategori']->nama_kategori }} ({{ $rekap['kategori']->jenis }})
    — Total Suara: {{ $rekap['total_suara'] }}
</div>

@if($rekap['kandidats']->count() > 0)
<table class="rekap">
    <thead>
        <tr>
            <th style="width:30px;">No.</th>
            <th>Ketua</th>
            <th>Wakil</th>
            <th style="width:70px;">Suara</th>
            <th style="width:180px;">Persentase</th>
            <th style="width:70px;">Ket.</th>
        </tr>
    </thead>
    <tbody>
        @php $maxSuara = $rekap['kandidats']->max('jumlah_suara'); @endphp
        @foreach($rekap['kandidats'] as $item)
        @php $isPemenang = $rekap['total_suara'] > 0 && $item['jumlah_suara'] == $maxSuara && $maxSuara > 0; @endphp
        <tr {{ $isPemenang ? 'class=pemenang' : '' }}>
            <td style="text-align:center;font-weight:bold;">{{ $item['kandidat']->nomor_urut }}</td>
            <td>
                {{ $item['kandidat']->nama_ketua }}<br>
                <span style="color:#666;font-size:10px;">NIM: {{ $item['kandidat']->nim_ketua }}</span>
            </td>
            <td>
                @if($item['kandidat']->nama_wakil)
                    {{ $item['kandidat']->nama_wakil }}<br>
                    <span style="color:#666;font-size:10px;">NIM: {{ $item['kandidat']->nim_wakil }}</span>
                @else
                    <span style="color:#999;">—</span>
                @endif
            </td>
            <td style="text-align:center;font-weight:bold;font-size:14px;">{{ $item['jumlah_suara'] }}</td>
            <td>
                <span class="progress-bar">
                    <span class="progress-fill" style="width:{{ $item['persentase'] }}%;"></span>
                </span>
                {{ $item['persentase'] }}%
            </td>
            <td>{{ $isPemenang ? '🏆 Unggul' : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color:#999;padding:8px 0;font-size:11px;">Belum ada kandidat.</p>
@endif
@endforeach

{{-- Tanda Tangan --}}
<div class="signature-area no-print" style="display:none;">
    <div class="signature-box">
        <p>Mengetahui,</p>
        <div class="line"></div>
        <p><strong>Ketua KPU</strong></p>
    </div>
    <div class="signature-box">
        <p>Ditetapkan di, ____________</p>
        <p>Tanggal: {{ now()->format('d F Y') }}</p>
        <div class="line"></div>
        <p><strong>{{ $admin->name_admin }}</strong></p>
        <p>{{ ucfirst($admin->role) }}</p>
    </div>
</div>

<div class="footer">
    Laporan ini digenerate otomatis oleh Sistem PEMIRA &mdash; {{ now()->format('d F Y, H:i') }} WIB
</div>

</body>
</html>