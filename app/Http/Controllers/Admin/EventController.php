<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\KategoriPemilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // List semua event
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $events = Event::withCount('kategoriPemilihan')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.event.index', compact('events', 'admin'));
    }

    // Form tambah event
    public function create()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        return view('admin.event.create', compact('admin'));
    }

    // Simpan event baru
    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->isSuperAdmin()) {
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

    // Form edit event
    public function edit($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $event = Event::with('kategoriPemilihan')->findOrFail($id);
        
        return view('admin.event.edit', compact('event', 'admin'));
    }

    // Update event
    public function update(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->isSuperAdmin()) {
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

    // Hapus event
    public function destroy($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $event = Event::findOrFail($id);
        $event->delete(); // Kategori akan terhapus otomatis (cascade)

        return redirect()->route('admin.event.index')
            ->with('success', 'Event berhasil dihapus!');
    }

    // Lihat detail event & kategorinya
    public function show($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $event = Event::with('kategoriPemilihan')->findOrFail($id);
        
        return view('admin.event.show', compact('event', 'admin'));
    }
}