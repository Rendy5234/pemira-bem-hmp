@extends('admin.layouts.app')

@section('title', 'Tambah Event')

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
    .form-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        max-width: 900px;
    }
    .section-title {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        margin-top: 30px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e5e7eb;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #374151;
        font-weight: bold;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 14px;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #2563eb;
    }
    .form-group small {
        color: #6b7280;
        font-size: 12px;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }
    .btn {
        padding: 12px 24px;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        border: none;
    }
    .btn-primary {
        background-color: #2563eb;
        color: white;
    }
    .btn-primary:hover {
        background-color: #1d4ed8;
    }
    .btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }
    .btn-secondary:hover {
        background-color: #d1d5db;
    }
    .btn-success {
        background-color: #10b981;
        color: white;
    }
    .btn-success:hover {
        background-color: #059669;
    }
    .error-message {
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
    }
    /* Kategori Pemilihan */
    .kategori-container {
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        padding: 20px;
        margin-bottom: 15px;
        background-color: #f9fafb;
    }
    .kategori-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .kategori-number {
        font-weight: bold;
        color: #2563eb;
    }
    .btn-remove {
        background-color: #ef4444;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        border: none;
    }
    .btn-remove:hover {
        background-color: #dc2626;
    }
</style>

<div class="page-header">
    <h1>Tambah Event Pemilihan</h1>
    <div class="breadcrumb">
        <a href="{{ route('admin.event.index') }}">Event</a> / Tambah Baru
    </div>
</div>

<div class="form-container">
    <form action="{{ route('admin.event.store') }}" method="POST" id="eventForm">
        @csrf
        
        <div class="section-title">Informasi Event</div>

        <div class="form-group">
            <label>Nama Event *</label>
            <input type="text" name="nama_event" value="{{ old('nama_event') }}" placeholder="Contoh: Pemilihan Raya BEM & HMP 2024" required>
            @error('nama_event')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Periode *</label>
            <input type="text" name="periode" value="{{ old('periode') }}" placeholder="Contoh: 2024/2025" required>
            @error('periode')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tanggal Mulai *</label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                @error('tanggal_mulai')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Tanggal Selesai *</label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                @error('tanggal_selesai')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Waktu Mulai *</label>
                <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required>
                @error('waktu_mulai')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Waktu Selesai *</label>
                <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required>
                @error('waktu_selesai')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3" placeholder="Deskripsi event (optional)">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Status *</label>
            <select name="status" required>
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <small>Draft = Belum aktif, Aktif = Sedang berlangsung, Selesai = Sudah berakhir</small>
            @error('status')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- KATEGORI PEMILIHAN -->
        <div class="section-title">Kategori Pemilihan</div>
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 20px;">
            Tambahkan kategori pemilihan (misal: BEM, HMP Informatika, HMP Sipil, dll). Minimal 1 kategori.
        </p>

        <div id="kategoriContainer">
            <!-- Kategori 1 (default) -->
            <div class="kategori-container" data-index="0">
                <div class="kategori-header">
                    <span class="kategori-number">Kategori #1</span>
                </div>
                
                <div class="form-group">
                    <label>Nama Kategori *</label>
                    <input type="text" name="kategori[0][nama]" value="{{ old('kategori.0.nama') }}" placeholder="Contoh: Ketua BEM" required>
                </div>

                <div class="form-group">
                    <label>Jenis *</label>
                    <select name="kategori[0][jenis]" required>
                        <option value="">Pilih Jenis</option>
                        <option value="BEM" {{ old('kategori.0.jenis') == 'BEM' ? 'selected' : '' }}>BEM</option>
                        <option value="HMP" {{ old('kategori.0.jenis') == 'HMP' ? 'selected' : '' }}>HMP</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="kategori[0][deskripsi]" rows="2" placeholder="Deskripsi kategori (optional)">{{ old('kategori.0.deskripsi') }}</textarea>
                </div>
            </div>
        </div>

        @error('kategori')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <button type="button" class="btn btn-success" id="addKategoriBtn">+ Tambah Kategori</button>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Event</button>
            <a href="{{ route('admin.event.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
let kategoriIndex = 1;

document.getElementById('addKategoriBtn').addEventListener('click', function() {
    const container = document.getElementById('kategoriContainer');
    
    const newKategori = document.createElement('div');
    newKategori.className = 'kategori-container';
    newKategori.setAttribute('data-index', kategoriIndex);
    newKategori.innerHTML = `
        <div class="kategori-header">
            <span class="kategori-number">Kategori #${kategoriIndex + 1}</span>
            <button type="button" class="btn-remove" onclick="removeKategori(this)">Hapus</button>
        </div>
        
        <div class="form-group">
            <label>Nama Kategori *</label>
            <input type="text" name="kategori[${kategoriIndex}][nama]" placeholder="Contoh: Ketua HMP Informatika" required>
        </div>

        <div class="form-group">
            <label>Jenis *</label>
            <select name="kategori[${kategoriIndex}][jenis]" required>
                <option value="">Pilih Jenis</option>
                <option value="BEM">BEM</option>
                <option value="HMP">HMP</option>
            </select>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="kategori[${kategoriIndex}][deskripsi]" rows="2" placeholder="Deskripsi kategori (optional)"></textarea>
        </div>
    `;
    
    container.appendChild(newKategori);
    kategoriIndex++;
});

function removeKategori(button) {
    const container = button.closest('.kategori-container');
    container.remove();
    updateKategoriNumbers();
}

function updateKategoriNumbers() {
    const containers = document.querySelectorAll('.kategori-container');
    containers.forEach((container, index) => {
        container.querySelector('.kategori-number').textContent = `Kategori #${index + 1}`;
    });
}
</script>
@endsection