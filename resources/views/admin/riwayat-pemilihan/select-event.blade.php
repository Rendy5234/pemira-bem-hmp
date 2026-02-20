@extends('admin.layouts.app')

@section('title', 'Pilih Event - Riwayat Pemilihan')

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

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
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

        .btn-select {
            background-color: #2563eb;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            font-weight: bold;
        }

        .btn-select:hover {
            background-color: #1d4ed8;
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

        .hidden-row {
            display: none !important;
        }

        .result-count {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }
    </style>

    <div class="page-header">
        <h1>📊 Pilih Event untuk Lihat Riwayat Pemilihan</h1>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <div class="filter-grid">
            <div class="filter-group">
                <label>🔍 Cari Event</label>
                <input type="text" name="search" id="searchInput" placeholder="Cari nama event..." autocomplete="off">
            </div>

            <div class="filter-group">
                <label>📅 Periode</label>
                <select name="periode" id="periodeSelect">
                    <option value="">Semua Periode</option>
                    @foreach($periodes as $periode)
                        <option value="{{ $periode }}">{{ $periode }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>📊 Status</label>
                <select name="status" id="statusSelect">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="aktif">Aktif</option>
                    <option value="selesai">Selesai</option>
                </select>
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

    @if($events->count() > 0)
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
                <tbody id="eventTableBody">
                    @foreach($events as $index => $event)
                        <tr class="event-row" data-nama="{{ strtolower($event->nama_event) }}" data-periode="{{ $event->periode }}"
                            data-status="{{ $event->status }}">
                            <td class="row-number">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $event->nama_event }}</strong>
                                @if($event->deskripsi)
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
                                <a href="{{ route('admin.riwayat-pemilihan.selectKategori', $event->id_event) }}"
                                    class="btn-select">
                                    Lihat Riwayat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- EMPTY STATE (akan ditampilkan via JavaScript) -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <p style="font-size: 18px; margin-bottom: 10px;">Tidak ada event yang sesuai filter</p>
                <p>Coba ubah kriteria pencarian atau <a href="javascript:void(0)" id="resetLink"
                        style="color: #2563eb; font-weight: bold;">reset filter</a></p>
            </div>
        </div>
    @else
        <div class="table-container">
            <div class="empty-state">
                <p style="font-size: 18px; margin-bottom: 10px;">Belum ada event</p>
                <p>Silakan buat event terlebih dahulu di menu Event</p>
            </div>
        </div>
    @endif

    <script>
        // Ambil elemen
        const searchInput = document.getElementById('searchInput');
        const periodeSelect = document.getElementById('periodeSelect');
        const statusSelect = document.getElementById('statusSelect');
        const resetBtn = document.getElementById('resetBtn');
        const activeFilters = document.getElementById('activeFilters');
        const filterTags = document.getElementById('filterTags');
        const resultCount = document.getElementById('resultCount');
        const eventRows = document.querySelectorAll('.event-row');
        const emptyState = document.getElementById('emptyState');
        const resetLink = document.getElementById('resetLink');

        // Total events
        const totalEvents = eventRows.length;

        // Function untuk filter real-time
        function filterEvents() {
            const searchValue = searchInput.value.toLowerCase().trim();
            const periodeValue = periodeSelect.value;
            const statusValue = statusSelect.value;

            let visibleCount = 0;
            let currentNumber = 1;

            // Filter setiap row
            eventRows.forEach(row => {
                const nama = row.getAttribute('data-nama');
                const periode = row.getAttribute('data-periode');
                const status = row.getAttribute('data-status');

                // Cek apakah row sesuai filter
                const matchSearch = searchValue === '' || nama.includes(searchValue);
                const matchPeriode = periodeValue === '' || periode === periodeValue;
                const matchStatus = statusValue === '' || status === statusValue;

                // Show/hide row
                if (matchSearch && matchPeriode && matchStatus) {
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
            updateFilterUI(searchValue, periodeValue, statusValue, visibleCount);
        }

        // Function untuk update UI (tags, reset button, result count)
        function updateFilterUI(searchValue, periodeValue, statusValue, visibleCount) {
            const hasFilter = searchValue !== '' || periodeValue !== '' || statusValue !== '';

            // Update filter tags
            if (hasFilter) {
                activeFilters.style.display = 'flex';

                let tagsHTML = '';
                if (searchValue !== '') {
                    tagsHTML += `<span class="filter-tag">🔍 "${searchValue}"</span>`;
                }
                if (periodeValue !== '') {
                    tagsHTML += `<span class="filter-tag">📅 ${periodeValue}</span>`;
                }
                if (statusValue !== '') {
                    const statusText = statusValue.charAt(0).toUpperCase() + statusValue.slice(1);
                    tagsHTML += `<span class="filter-tag">📊 ${statusText}</span>`;
                }
                filterTags.innerHTML = tagsHTML;
            } else {
                activeFilters.style.display = 'none';
            }

            // Update result count
            if (hasFilter) {
                resultCount.textContent = `Menampilkan ${visibleCount} dari ${totalEvents} event`;
                resultCount.style.display = 'block';
            } else {
                resultCount.textContent = `Total ${totalEvents} event`;
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
            periodeSelect.value = '';
            statusSelect.value = '';
            filterEvents();
        }

        // Event listeners
        searchInput.addEventListener('input', filterEvents);
        periodeSelect.addEventListener('change', filterEvents);
        statusSelect.addEventListener('change', filterEvents);
        resetBtn.addEventListener('click', resetFilter);
        resetLink.addEventListener('click', resetFilter);

        // Initial count display
        resultCount.textContent = `Total ${totalEvents} event`;
        resultCount.style.display = 'block';
    </script>
@endsection