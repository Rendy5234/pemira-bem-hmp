@extends('admin.layouts.app')

@section('title', 'Riwayat Pemilihan')

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

        /* FILTER SECTION */
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }

        /* BARIS 1: 3 kolom */
        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 15px;
            align-items: end;
            margin-bottom: 15px;
        }
        
        /* BARIS 2: 3 kolom */
        .filter-grid-row2 {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
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

        .result-count {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .table-container {
            background: white;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th {
            background-color: #f9fafb;
            padding: 12px 15px;
            text-align: left;
            font-weight: bold;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            font-size: 14px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            font-size: 14px;
        }

        tr:hover {
            background-color: #f9fafb;
            cursor: pointer;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-BEM {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-HMP {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #9ca3af;
        }

        .hidden-row {
            display: none !important;
        }

        .time-ago {
            font-size: 12px;
            color: #9ca3af;
        }
    </style>

    <div class="page-header">
        <h1>Riwayat Pemilihan</h1>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <!-- BARIS 1: Search, Event, Kategori -->
        <div class="filter-grid">
            <div class="filter-group">
                <label>🔍 Cari Mahasiswa</label>
                <input type="text" name="search" id="searchInput" placeholder="Cari nama atau NIM mahasiswa..."
                    autocomplete="off">
            </div>

            <div class="filter-group">
                <label>📅 Event</label>
                <select name="event" id="eventSelect">
                    <option value="">Semua Event</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id_event }}">{{ $event->nama_event }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>📋 Kategori</label>
                <select name="kategori" id="kategoriSelect">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- BARIS 2: Fakultas, Prodi, Reset Button -->
        <div class="filter-grid-row2">
            <div class="filter-group">
                <label>🏛️ Fakultas</label>
                <select name="fakultas" id="fakultasSelect">
                    <option value="">Semua Fakultas</option>
                    @foreach($fakultasList as $fakultas)
                        <option value="{{ $fakultas }}">{{ $fakultas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>📚 Prodi</label>
                <select name="prodi" id="prodiSelect">
                    <option value="">Semua Prodi</option>
                    @foreach($prodiList as $prodi)
                        <option value="{{ $prodi }}">{{ $prodi }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="button" id="resetBtn" class="btn-reset">
                    🔄 Reset
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
                        <th style="width: 50px;">No</th>
                        <th style="width: 140px;">Waktu</th>
                        <th style="width: 110px;">NIM</th>
                        <th style="width: 180px;">Nama Mahasiswa</th>
                        <th style="width: 200px;">Event</th>
                        <th style="width: 180px;">Kategori</th>
                        <th style="width: 200px;">Kandidat Dipilih</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pemilihanTableBody">
                    @foreach($pemilihans as $index => $pemilihan)
                        <tr class="pemilihan-row" data-search="{{ strtolower($pemilihan->nim . ' ' . $pemilihan->nama_pemilih) }}"
                            data-event="{{ $pemilihan->id_event }}" data-kategori="{{ $pemilihan->id_kategori }}"
                            data-fakultas="{{ strtolower($pemilihan->user->fakultas ?? '') }}"
                            data-prodi="{{ strtolower($pemilihan->user->prodi ?? '') }}"
                            onclick="window.location.href='{{ route('admin.riwayat-pemilihan.show', $pemilihan->id_pemilihan) }}'">
                            <td class="row-number">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $pemilihan->waktu_pemilihan->format('d/m/Y') }}</strong><br>
                                <span class="time-ago">{{ $pemilihan->waktu_pemilihan->format('H:i') }}</span>
                            </td>
                            <td><strong>{{ $pemilihan->nim }}</strong></td>
                            <td>
                                {{ $pemilihan->nama_pemilih }}
                                @if($pemilihan->user)
                                    <div style="font-size: 11px; color: #9ca3af;">
                                        {{ $pemilihan->user->prodi ?? '' }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $pemilihan->event->nama_event ?? '-' }}</strong><br>
                                <span style="font-size: 11px; color: #9ca3af;">{{ $pemilihan->event->periode ?? '' }}</span>
                            </td>
                            <td>
                                {{ $pemilihan->kategoriPemilihan->nama_kategori ?? '-' }}
                                <span class="badge badge-{{ $pemilihan->kategoriPemilihan->jenis ?? '' }}">
                                    {{ $pemilihan->kategoriPemilihan->jenis ?? '' }}
                                </span>
                            </td>
                            <td>
                                <strong>No. {{ $pemilihan->kandidat->nomor_urut ?? '-' }}</strong><br>
                                <span style="font-size: 12px;">{{ $pemilihan->kandidat->nama_ketua ?? '-' }}</span>
                            </td>
                            <td onclick="event.stopPropagation()">
                                <a href="{{ route('admin.riwayat-pemilihan.show', $pemilihan->id_pemilihan) }}"
                                    class="badge badge-info" style="text-decoration: none; cursor: pointer; padding: 6px 12px;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- EMPTY STATE -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <p style="font-size: 18px; margin-bottom: 10px;">Tidak ada data yang sesuai filter</p>
                <p>Coba ubah kriteria pencarian atau <a href="javascript:void(0)" id="resetLink"
                        style="color: #2563eb; font-weight: bold;">reset filter</a></p>
            </div>
        </div>

        <!-- PAGINATION -->
        @if($pemilihans->hasPages())
            <div style="margin-top: 20px;">
                {{ $pemilihans->links() }}
            </div>
        @endif

    @else
        <div class="table-container">
            <div class="empty-state">
                <p style="font-size: 18px; margin-bottom: 10px;">Belum ada data pemilihan</p>
                <p>Belum ada mahasiswa yang melakukan pemilihan. Run PemilihanSeeder untuk membuat data dummy.</p>
            </div>
        </div>
    @endif

    <script>
        // Ambil elemen
        const searchInput = document.getElementById('searchInput');
        const eventSelect = document.getElementById('eventSelect');
        const kategoriSelect = document.getElementById('kategoriSelect');
        const fakultasSelect = document.getElementById('fakultasSelect');
        const prodiSelect = document.getElementById('prodiSelect');
        const resetBtn = document.getElementById('resetBtn');
        const activeFilters = document.getElementById('activeFilters');
        const filterTags = document.getElementById('filterTags');
        const resultCount = document.getElementById('resultCount');
        const pemilihanRows = document.querySelectorAll('.pemilihan-row');
        const emptyState = document.getElementById('emptyState');
        const resetLink = document.getElementById('resetLink');

        const totalPemilihan = pemilihanRows.length;

        // Function untuk filter real-time
        function filterPemilihan() {
            const searchValue = searchInput.value.toLowerCase().trim();
            const eventValue = eventSelect.value;
            const kategoriValue = kategoriSelect.value;
            const fakultasValue = fakultasSelect.value.toLowerCase();
            const prodiValue = prodiSelect.value.toLowerCase();

            let visibleCount = 0;
            let currentNumber = 1;

            pemilihanRows.forEach(row => {
                const search = row.getAttribute('data-search');
                const event = row.getAttribute('data-event');
                const kategori = row.getAttribute('data-kategori');
                const fakultas = row.getAttribute('data-fakultas');
                const prodi = row.getAttribute('data-prodi');

                const matchSearch = searchValue === '' || search.includes(searchValue);
                const matchEvent = eventValue === '' || event === eventValue;
                const matchKategori = kategoriValue === '' || kategori === kategoriValue;
                const matchFakultas = fakultasValue === '' || fakultas.includes(fakultasValue);
                const matchProdi = prodiValue === '' || prodi.includes(prodiValue);

                if (matchSearch && matchEvent && matchKategori && matchFakultas && matchProdi) {
                    row.classList.remove('hidden-row');
                    row.querySelector('.row-number').textContent = currentNumber;
                    currentNumber++;
                    visibleCount++;
                } else {
                    row.classList.add('hidden-row');
                }
            });

            updateFilterUI(searchValue, eventValue, kategoriValue, fakultasValue, prodiValue, visibleCount);
        }

        // Function untuk update UI
        function updateFilterUI(searchValue, eventValue, kategoriValue, fakultasValue, prodiValue, visibleCount) {
            const hasFilter = searchValue !== '' || eventValue !== '' || kategoriValue !== '' || fakultasValue !== '' || prodiValue !== '';

            if (hasFilter) {
                activeFilters.style.display = 'flex';

                let tagsHTML = '';
                if (searchValue !== '') {
                    tagsHTML += `<span class="filter-tag">🔍 "${searchValue}"</span>`;
                }
                if (eventValue !== '') {
                    const selectedText = eventSelect.options[eventSelect.selectedIndex].text;
                    tagsHTML += `<span class="filter-tag">📅 ${selectedText}</span>`;
                }
                if (kategoriValue !== '') {
                    const selectedText = kategoriSelect.options[kategoriSelect.selectedIndex].text;
                    tagsHTML += `<span class="filter-tag">📋 ${selectedText}</span>`;
                }
                if (fakultasValue !== '') {
                    tagsHTML += `<span class="filter-tag">🏛️ ${fakultasValue}</span>`;
                }
                if (prodiValue !== '') {
                    tagsHTML += `<span class="filter-tag">📚 ${prodiValue}</span>`;
                }
                filterTags.innerHTML = tagsHTML;
            } else {
                activeFilters.style.display = 'none';
            }

            if (hasFilter) {
                resultCount.textContent = `Menampilkan ${visibleCount} dari ${totalPemilihan} pemilihan`;
                resultCount.style.display = 'block';
            } else {
                resultCount.textContent = `Total ${totalPemilihan} pemilihan`;
                resultCount.style.display = 'block';
            }

            if (visibleCount === 0 && hasFilter) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }

        // Function untuk reset filter
        function resetFilter() {
            searchInput.value = '';
            eventSelect.value = '';
            kategoriSelect.value = '';
            fakultasSelect.value = '';
            prodiSelect.value = '';
            filterPemilihan();
        }

        // Event listeners
        searchInput.addEventListener('input', filterPemilihan);
        eventSelect.addEventListener('change', filterPemilihan);
        kategoriSelect.addEventListener('change', filterPemilihan);
        fakultasSelect.addEventListener('change', filterPemilihan);
        prodiSelect.addEventListener('change', filterPemilihan);
        resetBtn.addEventListener('click', resetFilter);
        resetLink.addEventListener('click', resetFilter);

        // Initial count display
        resultCount.textContent = `Total ${totalPemilihan} pemilihan`;
        resultCount.style.display = 'block';
    </script>
@endsection