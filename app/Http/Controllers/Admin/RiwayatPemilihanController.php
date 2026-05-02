<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemilihan;
use App\Models\Event;
use App\Models\KategoriPemilihan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPemilihanController extends Controller
{
    // ========== DAFTAR PEMILIHAN (dengan info mahasiswa) ==========
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // Ambil semua data pemilihan dengan relasi
        $pemilihans = Pemilihan::with(['event', 'kategoriPemilihan', 'kandidat', 'user'])
            ->orderBy('waktu_pemilihan', 'desc')
            ->paginate(50);
        
        // Statistik
        $totalPemilihan = Pemilihan::count();
        $totalMahasiswa = Pemilihan::distinct('id_user')->count('id_user');
        $totalEvent = Pemilihan::distinct('id_event')->count('id_event');
        $totalKategori = Pemilihan::distinct('id_kategori')->count('id_kategori');
        
        // Data untuk filter
        $events = Event::orderBy('nama_event')->get();
        $kategoris = KategoriPemilihan::orderBy('nama_kategori')->get();
        
        // Ambil list fakultas dan prodi dari users yang pernah memilih
        $userIds = Pemilihan::distinct('id_user')->pluck('id_user');
        
        $fakultasList = User::whereIn('id', $userIds)
            ->whereNotNull('fakultas')
            ->distinct()
            ->pluck('fakultas')
            ->filter()
            ->sort()
            ->values();
            
        $prodiList = User::whereIn('id', $userIds)
            ->whereNotNull('prodi')
            ->distinct()
            ->pluck('prodi')
            ->filter()
            ->sort()
            ->values();
        
        return view('admin.riwayat-pemilihan.index', compact(
            'admin',
            'pemilihans',
            'totalPemilihan',
            'totalMahasiswa',
            'totalEvent',
            'totalKategori',
            'events',
            'kategoris',
            'fakultasList',
            'prodiList'
        ));
    }
    
    // ========== DETAIL PEMILIHAN ==========
    public function show($id_pemilihan)
    {
        $admin = Auth::guard('admin')->user();
        
        $pemilihan = Pemilihan::with(['event', 'kategoriPemilihan', 'kandidat', 'user'])
            ->findOrFail($id_pemilihan);
        
        // Ambil event dan kategori dari relasi pemilihan
        $event = $pemilihan->event;
        $kategori = $pemilihan->kategoriPemilihan;
        
        return view('admin.riwayat-pemilihan.show', compact('admin', 'pemilihan', 'event', 'kategori'));
    }
    
    // ========== SOFT DELETE (Super Admin Only) ==========
    public function destroy($id_pemilihan)
    {
        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $pemilihan = Pemilihan::findOrFail($id_pemilihan);
        $pemilihan->delete();

        return redirect()->route('admin.riwayat-pemilihan.index')
            ->with('success', 'Data pemilihan berhasil dihapus (masuk ke Trash).');
    }
    
    // ========== TRASH ==========
    public function trash()
    {
        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $admin = Auth::guard('admin')->user();
        
        $pemilihans = Pemilihan::onlyTrashed()
            ->with(['event', 'kategoriPemilihan', 'kandidat', 'user'])
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        return view('admin.riwayat-pemilihan.trash', compact('admin', 'pemilihans'));
    }
    
    // ========== RESTORE ==========
    public function restore($id_pemilihan)
    {
        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $pemilihan = Pemilihan::onlyTrashed()->findOrFail($id_pemilihan);
        $pemilihan->restore();

        return redirect()->route('admin.riwayat-pemilihan.trash')
            ->with('success', 'Data pemilihan berhasil dipulihkan.');
    }
    
    // ========== FORCE DELETE ==========
    public function forceDelete($id_pemilihan)
    {
        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $pemilihan = Pemilihan::onlyTrashed()->findOrFail($id_pemilihan);
        $pemilihan->forceDelete();

        return redirect()->route('admin.riwayat-pemilihan.trash')
            ->with('success', 'Data pemilihan berhasil dihapus permanen.');
    }
}