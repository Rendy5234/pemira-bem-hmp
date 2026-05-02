@extends('admin.layouts.app')

@section('title', 'Laporan')

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
    .result-count {
        font-size: 14px;
        color: #6b7280;
        margin-top: 5px;
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
    .badge-draft   { background-color: #fef3c7; color: #92400e; }
    .badge-aktif   { background-color: #d1fae5; color: #065f46; }
    .badge-selesai { background-color: #e5e7eb; color: #374151; }
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
    .btn-view:hover { background-color: #2563eb; }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #9ca3af;
    }
    .hidden-row { display: none !important; }
    .kategori-count {
        font-size: 12px;
        color: #6b7280;
        margin-top: 5px;
    }
</style>

<div class="page-header">
    <h1>Laporan Hasil Pemilihan</h1>
</div>

<!-- FILTER SECTION -->
<div class="filter-section">
    <div class="filter-grid">
        <div class="filter-group">
            <label>🔍 Cari Event</label>
            <input type="text"
                   id="searchInput"
                   placeholder="Cari nama event..."
                   autocomplete="off">
        </div>

        <div class="filter-group">
            <label>📅 Periode</label>
            <select id="periodeSelect">
                <option value="">Semua Periode</option>
                @foreach($periodes as $periode)
                <option value="{{ $periode }}">{{ $periode }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label>📊 Status</label>
            <select id="statusSelect">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="aktif">Aktif</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>

        <div>
            <button type="button" id="resetBtn" class="btn-reset">
                Reset Filter
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
                <th>Kategori Pemilihan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="eventTableBody">
            @foreach($events as $index => $event)
            <tr class="event-row"
                data-href="{{ route('admin.laporan.detail', $event->id_event) }}"
                data-nama="{{ strtolower($event->nama_event) }}"
                data-periode="{{ $event->periode }}"
                data-status="{{ $event->status }}">
                <td class="row-number">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $event->nama_event }}</strong>
                    @if($event->deskripsi)
                    <div style="font-size: 12px; color: #6b7280; margin-top: 3px;">{{ Str::limit($event->deskripsi, 50) }}</div>
                    @endif
                </td>
                <td><strong>{{ $event->periode }}</strong></td>
                <td>
                    {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d F Y') }}<br>
                    <small style="color: #9ca3af;">s/d {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d F Y') }}</small>
                </td>
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
                    <a href="{{ route('admin.laporan.detail', $event->id_event) }}" class="btn-action btn-view">Lihat</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="empty-state" id="emptyState" style="display: none;">
        <p style="font-size: 18px; margin-bottom: 10px;">Tidak ada event yang sesuai filter</p>
        <p>Coba ubah kriteria pencarian atau <a href="javascript:void(0)" id="resetLink" style="color: #2563eb; font-weight: bold;">reset filter</a></p>
    </div>
</div>
@else
<div class="table-container">
    <div class="empty-state">
        <p style="font-size: 18px; margin-bottom: 10px;">Belum ada event</p>
        <p>Buat event terlebih dahulu di menu Event</p>
    </div>
</div>
@endif

<script>
const searchInput   = document.getElementById('searchInput');
const periodeSelect = document.getElementById('periodeSelect');
const statusSelect  = document.getElementById('statusSelect');
const resetBtn      = document.getElementById('resetBtn');
const activeFilters = document.getElementById('activeFilters');
const filterTags    = document.getElementById('filterTags');
const resultCount   = document.getElementById('resultCount');
const eventRows     = document.querySelectorAll('.event-row');
const emptyState    = document.getElementById('emptyState');
const resetLink     = document.getElementById('resetLink');

const totalEvents = eventRows.length;

function filterEvents() {
    const searchValue  = searchInput.value.toLowerCase().trim();
    const periodeValue = periodeSelect.value;
    const statusValue  = statusSelect.value;

    let visibleCount  = 0;
    let currentNumber = 1;

    eventRows.forEach(row => {
        const nama    = row.getAttribute('data-nama');
        const periode = row.getAttribute('data-periode');
        const status  = row.getAttribute('data-status');

        const matchSearch  = searchValue === '' || nama.includes(searchValue);
        const matchPeriode = periodeValue === '' || periode === periodeValue;
        const matchStatus  = statusValue === '' || status === statusValue;

        if (matchSearch && matchPeriode && matchStatus) {
            row.classList.remove('hidden-row');
            row.querySelector('.row-number').textContent = currentNumber++;
            visibleCount++;
        } else {
            row.classList.add('hidden-row');
        }
    });

    updateFilterUI(searchValue, periodeValue, statusValue, visibleCount);
}

function updateFilterUI(searchValue, periodeValue, statusValue, visibleCount) {
    const hasFilter = searchValue !== '' || periodeValue !== '' || statusValue !== '';

    if (hasFilter) {
        activeFilters.style.display = 'flex';
        let tagsHTML = '';
        if (searchValue)  tagsHTML += `<span class="filter-tag">🔍 "${searchValue}"</span>`;
        if (periodeValue) tagsHTML += `<span class="filter-tag">📅 ${periodeValue}</span>`;
        if (statusValue)  tagsHTML += `<span class="filter-tag">📊 ${statusValue.charAt(0).toUpperCase() + statusValue.slice(1)}</span>`;
        filterTags.innerHTML = tagsHTML;
    } else {
        activeFilters.style.display = 'none';
    }

    resultCount.textContent = hasFilter
        ? `Menampilkan ${visibleCount} dari ${totalEvents} event`
        : `Total ${totalEvents} event`;
    resultCount.style.display = 'block';

    emptyState.style.display = (visibleCount === 0 && hasFilter) ? 'block' : 'none';
}

function resetFilter() {
    searchInput.value  = '';
    periodeSelect.value = '';
    statusSelect.value  = '';
    filterEvents();
}

// Clickable row
eventRows.forEach(row => {
    row.addEventListener('click', function(e) {
        if (!e.target.closest('a, button, form')) {
            window.location = this.dataset.href;
        }
    });
});

searchInput.addEventListener('input', filterEvents);
periodeSelect.addEventListener('change', filterEvents);
statusSelect.addEventListener('change', filterEvents);
resetBtn.addEventListener('click', resetFilter);
resetLink?.addEventListener('click', resetFilter);

// Initial count
resultCount.textContent = `Total ${totalEvents} event`;
resultCount.style.display = 'block';
</script>
@endsection