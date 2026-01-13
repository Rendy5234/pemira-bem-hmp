<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\KategoriPemilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Helper method untuk cek akses
    private function checkAccess($allowAdmin = false)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->isSuperAdmin()) {
            return true;
        }
        
        if ($allowAdmin && $admin->isAdmin()) {
            return true;
        }
        
        return false;
    }

    // List semua event (Admin & Super Admin bisa akses)
    public function index()
    {
        if (!$this->checkAccess(true)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $admin = Auth::guard('admin')->user();
        $events = Event::withCount('kategoriPemilihan')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.event.index', compact('events', 'admin'));
    }

    // Form tambah event (Admin & Super Admin bisa akses)
    public function create()
    {
        if (!$this->checkAccess(true)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $admin = Auth::guard('admin')->user();
        return view('admin.event.create', compact('admin'));
    }

    // Simpan event baru (Admin & Super Admin bisa akses)
    public function store(Request $request)
    {
        if (!$this->checkAccess(true)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'nama_event' => 'required|max:255',
            'periode' => 'required|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'deskripsi' => 'nullable',
            'status' => 'required|in:draft,aktif,selesai',
            'kategori' => 'required|array|min:1',
            'kategori.*.nama' => 'required|max:255',
            'kategori.*.jenis' => 'required|in:BEM,HMP',
            'kategori.*.deskripsi' => 'nullable',
        ], [
            'kategori.required' => 'Minimal harus ada 1 kategori pemilihan',
            'kategori.*.nama.required' => 'Nama kategori wajib diisi',
            'kategori.*.jenis.required' => 'Jenis kategori wajib dipilih',
        ]);

        // Simpan event
        $event = Event::create([
            'nama_event' => $validated['nama_event'],
            'periode' => $validated['periode'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'deskripsi' => $validated['deskripsi'],
            'status' => $validated['status'],
        ]);

        // Simpan kategori pemilihan
        foreach ($validated['kategori'] as $kategori) {
            KategoriPemilihan::create([
                'id_event' => $event->id_event,
                'nama_kategori' => $kategori['nama'],
                'jenis' => $kategori['jenis'],
                'deskripsi' => $kategori['deskripsi'] ?? null,
            ]);
        }

        return redirect()->route('admin.event.index')
            ->with('success', 'Event berhasil ditambahkan!');
    }

    // Form edit event (Admin & Super Admin bisa akses)
    public function edit($id)
    {
        if (!$this->checkAccess(true)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $admin = Auth::guard('admin')->user();
        $event = Event::with('kategoriPemilihan')->findOrFail($id);
        
        return view('admin.event.edit', compact('event', 'admin'));
    }

    // Update event (Admin & Super Admin bisa akses)
    public function update(Request $request, $id)
    {
        if (!$this->checkAccess(true)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'nama_event' => 'required|max:255',
            'periode' => 'required|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'deskripsi' => 'nullable',
            'status' => 'required|in:draft,aktif,selesai',
            'kategori' => 'required|array|min:1',
            'kategori.*.nama' => 'required|max:255',
            'kategori.*.jenis' => 'required|in:BEM,HMP',
            'kategori.*.deskripsi' => 'nullable',
        ]);

        // Update event
        $event->update([
            'nama_event' => $validated['nama_event'],
            'periode' => $validated['periode'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'deskripsi' => $validated['deskripsi'],
            'status' => $validated['status'],
        ]);

        // Hapus kategori lama, bikin baru
        $event->kategoriPemilihan()->delete();
        
        foreach ($validated['kategori'] as $kategori) {
            KategoriPemilihan::create([
                'id_event' => $event->id_event,
                'nama_kategori' => $kategori['nama'],
                'jenis' => $kategori['jenis'],
                'deskripsi' => $kategori['deskripsi'] ?? null,
            ]);
        }

        return redirect()->route('admin.event.index')
            ->with('success', 'Event berhasil diupdate!');
    }

    // Hapus event (HANYA Super Admin yang bisa)
    public function destroy($id)
    {
        $admin = Auth::guard('admin')->user();
        
        // Cek hanya superadmin
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Hanya Super Admin yang dapat menghapus event!');
        }

        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.event.index')
            ->with('success', 'Event berhasil dihapus!');
    }

    // Lihat detail event (Admin & Super Admin bisa akses)
    public function show($id)
    {
        if (!$this->checkAccess(true)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $admin = Auth::guard('admin')->user();
        $event = Event::with('kategoriPemilihan')->findOrFail($id);
        
        return view('admin.event.show', compact('event', 'admin'));
    }
}