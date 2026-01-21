@extends('admin.layouts.app')

@section('title', 'Edit Event')

@section('content')
<style>
    /* Copy semua style dari create.blade.php yang sudah ada */
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
    <h1>Edit Event Pemilihan</h1>
    <div class="breadcrumb">
        <a href="{{ route('admin.event.index') }}">Event</a> / Edit
    </div>
</div>

<div class="form-container">
    <form action="{{ route('admin.event.update', $event->id_event) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="section-title">Informasi Event</div>

        <div class="form-group">
            <label>Nama Event *</label>
            <input type="text" name="nama_event" value="{{ old('nama_event', $event->nama_event) }}" required>
            @error('nama_event')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Periode *</label>
            <input type="text" name="periode" value="{{ old('periode', $event->periode) }}" required>
            @error('periode')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tanggal Mulai *</label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $event->tanggal_mulai->format('Y-m-d')) }}" required>
                @error('tanggal_mulai')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Tanggal Selesai *</label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $event->tanggal_selesai->format('Y-m-d')) }}" required>
                @error('tanggal_selesai')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Waktu Mulai *</label>
                <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', $event->waktu_mulai) }}" required>
                @error('waktu_mulai')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Waktu Selesai *</label>
                <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $event->waktu_selesai) }}" required>
                @error('waktu_selesai')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3">{{ old('deskripsi', $event->deskripsi) }}</textarea>
            @error('deskripsi')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Status *</label>
            <select name="status" required>
                <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="aktif" {{ old('status', $event->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ old('status', $event->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <small>Draft = Belum aktif, Aktif = Sedang berlangsung, Selesai = Sudah berakhir</small>
            @error('status')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- KATEGORI PEMILIHAN -->
        <div class="section-title">Kategori Pemilihan</div>
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 20px;">
            Edit kategori pemilihan. Minimal 1 kategori.
        </p>

        <div id="kategoriContainer">
            @foreach($event->kategoriPemilihan as $index => $kategori)
            <div class="kategori-container" data-index="{{ $index }}">
                <!-- PENTING: Hidden input untuk ID kategori yang sudah ada -->
                <input type="hidden" name="kategori[{{ $index }}][id]" value="{{ $kategori->id_kategori }}">
                
                <div class="kategori-header">
                    <span class="kategori-number">Kategori #{{ $index + 1 }}</span>
                    @if($index > 0)
                    <button type="button" class="btn-remove" onclick="removeKategori(this)">Hapus</button>
                    @endif
                </div>
                
                <div class="form-group">
                    <label>Nama Kategori *</label>
                    <input type="text" name="kategori[{{ $index }}][nama]" value="{{ old('kategori.'.$index.'.nama', $kategori->nama_kategori) }}" required>
                </div>

                <div class="form-group">
                    <label>Jenis *</label>
                    <select name="kategori[{{ $index }}][jenis]" required>
                        <option value="BEM" {{ old('kategori.'.$index.'.jenis', $kategori->jenis) == 'BEM' ? 'selected' : '' }}>BEM</option>
                        <option value="HMP" {{ old('kategori.'.$index.'.jenis', $kategori->jenis) == 'HMP' ? 'selected' : '' }}>HMP</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="kategori[{{ $index }}][deskripsi]" rows="2">{{ old('kategori.'.$index.'.deskripsi', $kategori->deskripsi) }}</textarea>
                </div>
            </div>
            @endforeach
        </div>

        @error('kategori')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <button type="button" class="btn btn-success" id="addKategoriBtn">+ Tambah Kategori</button>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="{{ route('admin.event.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
let kategoriIndex = {{ $event->kategoriPemilihan->count() }};

document.getElementById('addKategoriBtn').addEventListener('click', function() {
    const container = document.getElementById('kategoriContainer');
    
    const newKategori = document.createElement('div');
    newKategori.className = 'kategori-container';
    newKategori.setAttribute('data-index', kategoriIndex);
    
    // TIDAK ADA hidden input ID untuk kategori baru (artinya ini CREATE)
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