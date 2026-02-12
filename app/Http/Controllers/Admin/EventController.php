<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\KategoriPemilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    // Helper method untuk cek akses
    private function checkReadAccess()
    {
        $admin = Auth::guard('admin')->user();
        return true; // Semua role bisa read
    }

    private function checkWriteAccess()
    {
        $admin = Auth::guard('admin')->user();
        return $admin->isSuperAdmin() || $admin->isAdmin();
    }

    private function checkDeleteAccess()
    {
        $admin = Auth::guard('admin')->user();
        return $admin->isSuperAdmin();
    }

    // List semua event
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = Event::withCount('kategoriPemilihan');
        
        // Filter Search (nama event)
        if ($request->filled('search')) {
            $query->where('nama_event', 'like', '%' . $request->search . '%');
        }
        
        // Filter Periode
        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }
        
        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $events = $query->orderBy('created_at', 'desc')->get();
        
        // Ambil list periode unik untuk dropdown
        $periodes = Event::select('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');
        
        return view('admin.event.index', compact('events', 'admin', 'periodes'));
    }

    // Form tambah event
    public function create()
    {
        if (!$this->checkWriteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Anda tidak memiliki akses untuk menambah event.');
        }

        $admin = Auth::guard('admin')->user();
        return view('admin.event.create', compact('admin'));
    }

    // Simpan event baru
    public function store(Request $request)
    {
        if (!$this->checkWriteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Anda tidak memiliki akses untuk menambah event.');
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
        if (!$this->checkWriteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit event.');
        }

        $admin = Auth::guard('admin')->user();
        $event = Event::with('kategoriPemilihan')->findOrFail($id);
        
        return view('admin.event.edit', compact('event', 'admin'));
    }

    // Update event - FIXED LOGIC
    public function update(Request $request, $id)
    {
        if (!$this->checkWriteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit event.');
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
            'kategori.*.id' => 'nullable|exists:tb_kategori_pemilihan,id_kategori',
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

        // SYNC KATEGORI (update existing, create new, delete removed)
        $existingKategoriIds = $event->kategoriPemilihan->pluck('id_kategori')->toArray();
        $submittedKategoriIds = [];

        foreach ($validated['kategori'] as $index => $kategoriData) {
            // Cari ID kategori dari hidden input (jika ada)
            $kategoriId = $request->input("kategori.{$index}.id");

            if ($kategoriId && in_array($kategoriId, $existingKategoriIds)) {
                // UPDATE kategori yang sudah ada
                $kategori = KategoriPemilihan::find($kategoriId);
                $kategori->update([
                    'nama_kategori' => $kategoriData['nama'],
                    'jenis' => $kategoriData['jenis'],
                    'deskripsi' => $kategoriData['deskripsi'] ?? null,
                ]);
                $submittedKategoriIds[] = $kategoriId;
            } else {
                // CREATE kategori baru
                $newKategori = KategoriPemilihan::create([
                    'id_event' => $event->id_event,
                    'nama_kategori' => $kategoriData['nama'],
                    'jenis' => $kategoriData['jenis'],
                    'deskripsi' => $kategoriData['deskripsi'] ?? null,
                ]);
                $submittedKategoriIds[] = $newKategori->id_kategori;
            }
        }

        // DELETE kategori yang dihapus dari form
        $kategoriToDelete = array_diff($existingKategoriIds, $submittedKategoriIds);
        if (!empty($kategoriToDelete)) {
            KategoriPemilihan::whereIn('id_kategori', $kategoriToDelete)->delete();
        }

        return redirect()->route('admin.event.index')
            ->with('success', 'Event berhasil diupdate!');
    }

    // Soft Delete (HANYA Super Admin)
    public function destroy($id)
    {
        if (!$this->checkDeleteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Hanya Super Admin yang dapat menghapus event!');
        }

        $event = Event::findOrFail($id);
        $event->delete(); // Soft delete

        return redirect()->route('admin.event.index')
            ->with('success', 'Event berhasil dihapus!');
    }

    // Lihat detail event
    public function show($id)
    {
        $admin = Auth::guard('admin')->user();
        $event = Event::with('kategoriPemilihan')->findOrFail($id);
        
        return view('admin.event.show', compact('event', 'admin'));
    }

    // Lihat event yang sudah dihapus (Trash)
    public function trash()
    {
        if (!$this->checkDeleteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $admin = Auth::guard('admin')->user();
        $events = Event::onlyTrashed()
            ->withCount('kategoriPemilihan')
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        return view('admin.event.trash', compact('events', 'admin'));
    }

    // Restore event
    public function restore($id)
    {
        if (!$this->checkDeleteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $event = Event::onlyTrashed()->findOrFail($id);
        $event->restore();

        return redirect()->route('admin.event.trash')
            ->with('success', 'Event berhasil dipulihkan!');
    }

    // Hapus permanen
    public function forceDelete($id)
    {
        if (!$this->checkDeleteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $event = Event::onlyTrashed()->findOrFail($id);
        $event->forceDelete();

        return redirect()->route('admin.event.trash')
            ->with('success', 'Event berhasil dihapus permanen!');
    }

    // ========== TRASH KATEGORI PEMILIHAN ==========
    
    // Lihat kategori yang sudah dihapus
    public function trashKategori()
    {
        if (!$this->checkDeleteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $admin = Auth::guard('admin')->user();
        $kategoris = KategoriPemilihan::onlyTrashed()
            ->with(['event' => function($query) {
                $query->withTrashed(); // Load event yang dihapus juga
            }])
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        return view('admin.event.trash-kategori', compact('kategoris', 'admin'));
    }

    // Restore kategori
    public function restoreKategori($id)
    {
        if (!$this->checkDeleteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $kategori = KategoriPemilihan::onlyTrashed()->findOrFail($id);
        $kategori->restore();

        return redirect()->route('admin.event.trashKategori')
            ->with('success', 'Kategori berhasil dipulihkan!');
    }

    // Hapus permanen kategori
    public function forceDeleteKategori($id)
    {
        if (!$this->checkDeleteAccess()) {
            return redirect()->route('admin.event.index')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $kategori = KategoriPemilihan::onlyTrashed()->findOrFail($id);
        $kategori->forceDelete();

        return redirect()->route('admin.event.trashKategori')
            ->with('success', 'Kategori berhasil dihapus permanen!');
    }
}