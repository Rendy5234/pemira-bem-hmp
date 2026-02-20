@extends('admin.layouts.app')

@section('title', 'Riwayat Pemilihan')

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

        .btn-danger {
            background-color: #ef4444;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-danger:hover {
            background-color: #dc2626;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 32px;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .stat-card p {
            color: #6b7280;
            font-size: 14px;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1.5fr 1.5fr auto;
            gap: 15px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #374151;
            font-size: 14px;
        }

        .filter-group input,
        .filter-group select {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #2563eb;
        }

        .btn-reset {
            background-color: #f59e0b;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            height: 42px;
            display: inline-flex;
            align-items: center;
            cursor: pointer;
            border: none;
            white-space: nowrap;
        }

        .btn-reset:hover {
            background-color: #d97706;
        }

        .active-filters {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-tag {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
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
        }

        .btn-view {
            background-color: #3b82f6;
            color: white;
        }

        .btn-view:hover {
            background-color: #2563eb;
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

        .hidden-row {
            display: none !important;
        }

        .result-count {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .nomor-urut {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
    </style>

    <div class="page-header">
        <h1>📊 Riwayat Pemilihan</h1>
        <div class="header-actions">
            <a href="{{ route('admin.riwayat-pemilihan.selectKategori', $event->id_event) }}" class="btn-back">← Kembali</a>
            @if($admin->isSuperAdmin())
                <a href="{{ route('admin.riwayat-pemilihan.trash', [$event->id_event, $kategori->id_kategori]) }}"
                    class="btn-danger">🗑️ Trash</a>
            @endif
        </div>
    </div>

    <!-- INFO CARD -->
    <div class="info-card">
        <h2>{{ $event->nama_event }} - {{ $kategori->nama_kategori }}</h2>
        <p><strong>📅 Periode:</strong> {{ $event->periode }}</p>
        <p><strong>📋 Jenis:</strong> {{ $kategori->jenis }}</p>
        <p><strong>🗓️ Tanggal Voting:</strong> {{ $event->getTanggalMulaiFormatted() }} -
            {{ $event->getTanggalSelesaiFormatted() }}</p>
    </div>

    <!-- STATISTIK -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>{{ $stats['total_pemilih'] }}</h3>
            <p>Total Pemilih</p>
        </div>
        @foreach($kandidats as $kandidat)
            <div class="stat-card">
                <h3>{{ $stats['kandidat_stats'][$kandidat->id_kandidat]->jumlah ?? 0 }}</h3>
                <p>Suara No. {{ $kandidat->nomor_urut }} - {{ $kandidat->nama_ketua }}</p>
            </div>
        @endforeach
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <div class="filter-grid">
            <div class="filter-group">
                <label>🔍 Cari Pemilih</label>
                <input type="text" name="search" id="searchInput" placeholder="Cari nama atau NIM pemilih..."
                    autocomplete="off">
            </div>

            <div class="filter-group">
                <label>🗳️ Kandidat Dipilih</label>
                <select name="kandidat" id="kandidatSelect">
                    <option value="">Semua Kandidat</option>
                    @foreach($kandidats as $kandidat)
                        <option value="{{ $kandidat->id_kandidat }}">
                            No. {{ $kandidat->nomor_urut }} - {{ $kandidat->nama_ketua }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>📅 Tanggal Voting</label>
                <input type="date" name="tanggal" id="tanggalInput">
            </div>

            <div>
                <button type="button" id="resetBtn" class="btn-reset">
                    🔄 Reset Filter
                </button>
            </div>
        </div>

        <!-- ACTIVE FILTERS TAG -->
        <div class="active-filters" id="activeFilters" style="display: none;">
            <strong style="color: #6b7280; font-size: 12px; align-self: center;">Filter aktif:</strong>
            <div id="filterTags"></div>
        </div>

        <!-- RESULT COUNT -->
        <div class="result-count" id="resultCount"></div>
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
                        <th>IP Address</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pemilihanTableBody">
                    @foreach($pemilihans as $index => $pemilihan)
                        <tr class="pemilihan-row" data-nama="{{ strtolower($pemilihan->nama_pemilih) }}"
                            data-nim="{{ strtolower($pemilihan->nim) }}" data-kandidat="{{ $pemilihan->id_kandidat }}"
                            data-tanggal="{{ $pemilihan->waktu_pemilihan->format('Y-m-d') }}">
                            <td class="row-number">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $pemilihan->waktu_pemilihan->format('d/m/Y') }}</strong><br>
                                <small style="color: #9ca3af;">{{ $pemilihan->waktu_pemilihan->format('H:i:s') }}</small>
                            </td>
                            <td><strong>{{ $pemilihan->nim }}</strong></td>
                            <td>{{ $pemilihan->nama_pemilih }}</td>
                            <td>
                                @if($pemilihan->kandidat)
                                    <div>
                                        <span class="nomor-urut">{{ $pemilihan->kandidat->nomor_urut }}</span>
                                        <strong>{{ $pemilihan->kandidat->nama_ketua }}</strong>
                                    </div>
                                    @if($pemilihan->kandidat->nama_wakil)
                                        <small style="color: #6b7280;">& {{ $pemilihan->kandidat->nama_wakil }}</small>
                                    @endif
                                @else
                                    <span style="color: #9ca3af;">-</span>
                                @endif
                            </td>
                            <td>
                                {{ $pemilihan->ip_address ?? '-' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.riwayat-pemilihan.show', [$event->id_event, $kategori->id_kategori, $pemilihan->id_pemilihan]) }}"
                                    class="btn-action btn-view">Detail</a>

                                @if($admin->isSuperAdmin())
                                    <form
                                        action="{{ route('admin.riwayat-pemilihan.destroy', [$event->id_event, $kategori->id_kategori, $pemilihan->id_pemilihan]) }}"
                                        method="POST" style="display: inline;"
                                        onsubmit="return confirm('Yakin ingin menghapus data pemilihan ini?')">
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

            <!-- EMPTY STATE (akan ditampilkan via JavaScript) -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <p style="font-size: 18px; margin-bottom: 10px;">Tidak ada data pemilihan yang sesuai filter</p>
                <p>Coba ubah kriteria pencarian atau <a href="javascript:void(0)" id="resetLink"
                        style="color: #2563eb; font-weight: bold;">reset filter</a></p>
            </div>
        </div>
    @else
        <div class="table-container">
            <div class="empty-state">
                <p style="font-size: 18px; margin-bottom: 10px;">Belum ada data pemilihan</p>
                <p>Data pemilihan akan muncul setelah ada mahasiswa yang voting</p>
            </div>
        </div>
    @endif

    <script>
        // Ambil elemen
        const searchInput = document.getElementById('searchInput');
        const kandidatSelect = document.getElementById('kandidatSelect');
        const tanggalInput = document.getElementById('tanggalInput');
        const resetBtn = document.getElementById('resetBtn');
        const activeFilters = document.getElementById('activeFilters');
        const filterTags = document.getElementById('filterTags');
        const resultCount = document.getElementById('resultCount');
        const pemilihanRows = document.querySelectorAll('.pemilihan-row');
        const emptyState = document.getElementById('emptyState');
        const resetLink = document.getElementById('resetLink');

        // Total pemilihan
        const totalPemilihan = pemilihanRows.length;

        // Function untuk filter real-time
        function filterPemilihan() {
            const searchValue = searchInput.value.toLowerCase().trim();
            const kandidatValue = kandidatSelect.value;
            const tanggalValue = tanggalInput.value;

            let visibleCount = 0;
            let currentNumber = 1;

            // Filter setiap row
            pemilihanRows.forEach(row => {
                const nama = row.getAttribute('data-nama');
                const nim = row.getAttribute('data-nim');
                const kandidat = row.getAttribute('data-kandidat');
                const tanggal = row.getAttribute('data-tanggal');

                // Cek apakah row sesuai filter
                const matchSearch = searchValue === '' || nama.includes(searchValue) || nim.includes(searchValue);
                const matchKandidat = kandidatValue === '' || kandidat === kandidatValue;
                const matchTanggal = tanggalValue === '' || tanggal === tanggalValue;

                // Show/hide row
                if (matchSearch && matchKandidat && matchTanggal) {
                    row.classList.remove('hidden-row');
                    // Update nomor urut
                    row.querySelector('.row-number').textContent = currentNumber;
                    currentNumber++;
                    visibleCount++;
                } else {
                    row.classList.add('hidden-row');
                }
            });

            // Update UI
            updateFilterUI(searchValue, kandidatValue, tanggalValue, visibleCount);
        }

        // Function untuk update UI (tags, reset button, result count)
        function updateFilterUI(searchValue, kandidatValue, tanggalValue, visibleCount) {
            const hasFilter = searchValue !== '' || kandidatValue !== '' || tanggalValue !== '';

            // Update filter tags
            if (hasFilter) {
                activeFilters.style.display = 'flex';

                let tagsHTML = '';
                if (searchValue !== '') {
                    tagsHTML += `<span class="filter-tag">🔍 "${searchValue}"</span>`;
                }
                if (kandidatValue !== '') {
                    const selectedOption = kandidatSelect.options[kandidatSelect.selectedIndex].text;
                    tagsHTML += `<span class="filter-tag">🗳️ ${selectedOption}</span>`;
                }
                if (tanggalValue !== '') {
                    tagsHTML += `<span class="filter-tag">📅 ${tanggalValue}</span>`;
                }
                filterTags.innerHTML = tagsHTML;
            } else {
                activeFilters.style.display = 'none';
            }

            // Update result count
            if (hasFilter) {
                resultCount.textContent = `Menampilkan ${visibleCount} dari ${totalPemilihan} data pemilihan`;
                resultCount.style.display = 'block';
            } else {
                resultCount.textContent = `Total ${totalPemilihan} data pemilihan`;
                resultCount.style.display = 'block';
            }

            // Show/hide empty state
            if (visibleCount === 0 && hasFilter) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }

        // Function untuk reset filter
        function resetFilter() {
            searchInput.value = '';
            kandidatSelect.value = '';
            tanggalInput.value = '';
            filterPemilihan();
        }

        // Event listeners
        searchInput.addEventListener('input', filterPemilihan);
        kandidatSelect.addEventListener('change', filterPemilihan);
        tanggalInput.addEventListener('change', filterPemilihan);
        resetBtn.addEventListener('click', resetFilter);
        resetLink.addEventListener('click', resetFilter);

        // Initial count display
        resultCount.textContent = `Total ${totalPemilihan} data pemilihan`;
        resultCount.style.display = 'block';
    </script>
@endsection