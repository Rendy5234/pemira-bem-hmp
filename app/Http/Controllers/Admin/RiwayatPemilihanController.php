<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemilihan;
use App\Models\Event;
use App\Models\KategoriPemilihan;
use App\Models\Kandidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiwayatPemilihanController extends Controller
{
    // ========== LEVEL 1: PILIH EVENT ==========
    public function selectEvent(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = Event::withCount('kategoriPemilihan');
        
        if ($request->filled('search')) {
            $query->where('nama_event', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $events = $query->orderBy('created_at', 'desc')->get();
        
        $periodes = Event::select('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');
        
        return view('admin.riwayat-pemilihan.select-event', compact('admin', 'events', 'periodes'));
    }

    // ========== LEVEL 2: PILIH KATEGORI ==========
    public function selectKategori($id_event)
    {
        $admin = Auth::guard('admin')->user();
        
        $event = Event::with('kategoriPemilihan')->findOrFail($id_event);
        
        $kategoris = $event->kategoriPemilihan->map(function ($kategori) {
            $kategori->jumlah_pemilihan = Pemilihan::where('id_kategori', $kategori->id_kategori)->count();
            return $kategori;
        });
        
        return view('admin.riwayat-pemilihan.select-kategori', compact('admin', 'event', 'kategoris'));
    }

    // ========== LEVEL 3: LIHAT RIWAYAT PEMILIHAN ==========
    public function index(Request $request, $id_event, $id_kategori)
    {
        $admin = Auth::guard('admin')->user();
        
        $event = Event::findOrFail($id_event);
        $kategori = KategoriPemilihan::findOrFail($id_kategori);
        
        $query = Pemilihan::with(['event', 'kategoriPemilihan', 'kandidat', 'user'])
            ->where('id_event', $id_event)
            ->where('id_kategori', $id_kategori);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_pemilih', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('kandidat')) {
            $query->where('id_kandidat', $request->kandidat);
        }
        
        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_pemilihan', $request->tanggal);
        }
        
        $pemilihans = $query->orderBy('waktu_pemilihan', 'desc')->get();
        
        $kandidats = Kandidat::where('id_kategori', $id_kategori)
            ->orderBy('nomor_urut')
            ->get();
        
        $stats = [
            'total_pemilih' => $pemilihans->count(),
            'kandidat_stats' => DB::table('tb_pemilihan')
                ->select('id_kandidat', DB::raw('COUNT(*) as jumlah'))
                ->where('id_event', $id_event)
                ->where('id_kategori', $id_kategori)
                ->whereNull('deleted_at')
                ->groupBy('id_kandidat')
                ->get()
                ->keyBy('id_kandidat')
        ];
        
        return view('admin.riwayat-pemilihan.index', compact('admin', 'event', 'kategori', 'pemilihans', 'kandidats', 'stats'));
    }

    // ========== DETAIL PEMILIHAN ==========
    public function show($id_event, $id_kategori, $id_pemilihan)
    {
        $admin = Auth::guard('admin')->user();
        
        $event = Event::findOrFail($id_event);
        $kategori = KategoriPemilihan::findOrFail($id_kategori);
        $pemilihan = Pemilihan::with(['event', 'kategoriPemilihan', 'kandidat', 'user'])
            ->findOrFail($id_pemilihan);
        
        return view('admin.riwayat-pemilihan.show', compact('admin', 'event', 'kategori', 'pemilihan'));
    }

    // ========== TRASH ==========
    public function trash($id_event, $id_kategori)
    {
        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $admin = Auth::guard('admin')->user();
        $event = Event::findOrFail($id_event);
        $kategori = KategoriPemilihan::findOrFail($id_kategori);
        
        $pemilihans = Pemilihan::onlyTrashed()
            ->where('id_event', $id_event)
            ->where('id_kategori', $id_kategori)
            ->with(['kandidat'])
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        return view('admin.riwayat-pemilihan.trash', compact('admin', 'event', 'kategori', 'pemilihans'));
    }

    // ========== RESTORE ==========
    public function restore($id_event, $id_kategori, $id_pemilihan)
    {
        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $pemilihan = Pemilihan::onlyTrashed()->findOrFail($id_pemilihan);
        $pemilihan->restore();

        return redirect()->route('admin.riwayat-pemilihan.trash', [$id_event, $id_kategori])
            ->with('success', 'Data pemilihan berhasil dipulihkan.');
    }

    // ========== FORCE DELETE ==========
    public function forceDelete($id_event, $id_kategori, $id_pemilihan)
    {
        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $pemilihan = Pemilihan::onlyTrashed()->findOrFail($id_pemilihan);
        $pemilihan->forceDelete();

        return redirect()->route('admin.riwayat-pemilihan.trash', [$id_event, $id_kategori])
            ->with('success', 'Data pemilihan berhasil dihapus permanen.');
    }

    // ========== SOFT DELETE ==========
    public function destroy($id_event, $id_kategori, $id_pemilihan)
    {
        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $pemilihan = Pemilihan::findOrFail($id_pemilihan);
        $pemilihan->delete();

        return redirect()->route('admin.riwayat-pemilihan.index', [$id_event, $id_kategori])
            ->with('success', 'Data pemilihan berhasil dihapus (masuk ke Trash).');
    }
}