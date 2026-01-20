@extends('admin.layouts.app')

@section('title', 'Edit Kandidat')

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
    .error-message {
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
    }
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-bem { background-color: #dbeafe; color: #1e40af; }
    .badge-hmp { background-color: #fce7f3; color: #9f1239; }
    .current-photo {
        margin-top: 10px;
        padding: 10px;
        background-color: #f9fafb;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
    }
    .current-photo img {
        max-width: 150px;
        border-radius: 4px;
        margin-top: 5px;
    }
</style>

<div class="page-header">
    <h1>Edit Kandidat #{{ $kandidat->nomor_urut }}</h1>
    <div class="breadcrumb">
        <a href="{{ route('admin.kandidat.selectEvent') }}">Pilih Event</a> / 
        <a href="{{ route('admin.kandidat.selectKategori', $event->id_event) }}">{{ $event->nama_event }}</a> / 
        <a href="{{ route('admin.kandidat.index', [$event->id_event, $kategori->id_kategori]) }}">{{ $kategori->nama_kategori }}</a> / 
        Edit
    </div>
</div>

<div class="form-container">
    <form action="{{ route('admin.kandidat.update', [$event->id_event, $kategori->id_kategori, $kandidat->id_kandidat]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <div style="margin-bottom: 10px;">
                <strong>Event:</strong> {{ $event->nama_event }} ({{ $event->periode }})
            </div>
            <div>
                <strong>Kategori:</strong> {{ $kategori->nama_kategori }} 
                <span class="badge badge-{{ strtolower($kategori->jenis) }}">{{ $kategori->jenis }}</span>
            </div>
        </div>

        <div class="section-title">Informasi Umum</div>

        <div class="form-group">
            <label>Nomor Urut *</label>
            <input type="number" name="nomor_urut" value="{{ old('nomor_urut', $kandidat->nomor_urut) }}" min="1" required>
            <small>Nomor urut kandidat (harus unik per kategori)</small>
            @error('nomor_urut')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="section-title">Data Ketua</div>

        <div class="form-row">
            <div class="form-group">
                <label>Nama Ketua *</label>
                <input type="text" name="nama_ketua" value="{{ old('nama_ketua', $kandidat->nama_ketua) }}" required>
                @error('nama_ketua')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>NIM Ketua *</label>
                <input type="text" name="nim_ketua" value="{{ old('nim_ketua', $kandidat->nim_ketua) }}" required>
                @error('nim_ketua')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Foto Ketua</label>
            <input type="file" name="foto_ketua" accept="image/*">
            <small>Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah foto.</small>
            @error('foto_ketua')
                <div class="error-message">{{ $message }}</div>
            @enderror
            
            @if($kandidat->foto_ketua)
            <div class="current-photo">
                <small style="color: #6b7280; font-weight: bold;">Foto saat ini:</small>
                <br><img src="{{ asset('storage/' . $kandidat->foto_ketua) }}" alt="Foto Ketua">
            </div>
            @endif
        </div>

        <div class="section-title">Data Wakil</div>

        <div class="form-row">
            <div class="form-group">
                <label>Nama Wakil *</label>
                <input type="text" name="nama_wakil" value="{{ old('nama_wakil', $kandidat->nama_wakil) }}" required>
                @error('nama_wakil')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>NIM Wakil *</label>
                <input type="text" name="nim_wakil" value="{{ old('nim_wakil', $kandidat->nim_wakil) }}" required>
                @error('nim_wakil')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Foto Wakil</label>
            <input type="file" name="foto_wakil" accept="image/*">
            <small>Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah foto.</small>
            @error('foto_wakil')
                <div class="error-message">{{ $message }}</div>
            @enderror
            
            @if($kandidat->foto_wakil)
            <div class="current-photo">
                <small style="color: #6b7280; font-weight: bold;">Foto saat ini:</small>
                <br><img src="{{ asset('storage/' . $kandidat->foto_wakil) }}" alt="Foto Wakil">
            </div>
            @endif
        </div>

        <div class="section-title">Visi & Misi</div>

        <div class="form-group">
            <label>Visi *</label>
            <textarea name="visi" rows="4" required>{{ old('visi', $kandidat->visi) }}</textarea>
            @error('visi')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Misi *</label>
            <textarea name="misi" rows="6" required>{{ old('misi', $kandidat->misi) }}</textarea>
            @error('misi')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Kandidat</button>
            <a href="{{ route('admin.kandidat.index', [$event->id_event, $kategori->id_kategori]) }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection