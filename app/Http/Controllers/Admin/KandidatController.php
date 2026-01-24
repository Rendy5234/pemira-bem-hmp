<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use App\Models\Event;
use App\Models\KategoriPemilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KandidatController extends Controller
{
    // Helper check access
    private function checkReadAccess()
    {
        return true;
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

    // ========== LEVEL 1: PILIH EVENT ==========
    public function selectEvent()
    {
        $admin = Auth::guard('admin')->user();
        $events = Event::withCount('kategoriPemilihan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.kandidat.select-event', compact('admin', 'events'));
    }

    // ========== LEVEL 2: PILIH KATEGORI ==========
    public function selectKategori($eventId)
    {
        $admin = Auth::guard('admin')->user();
        $event = Event::with('kategoriPemilihan')->findOrFail($eventId);

        return view('admin.kandidat.select-kategori', compact('admin', 'event'));
    }

    // ========== LEVEL 3: LIST KANDIDAT ==========
    public function index($eventId, $kategoriId)
    {
        $admin = Auth::guard('admin')->user();
        $event = Event::findOrFail($eventId);
        $kategori = KategoriPemilihan::findOrFail($kategoriId);

        $kandidats = Kandidat::where('id_kategori', $kategoriId)
            ->orderBy('nomor_urut')
            ->get();

        return view('admin.kandidat.index', compact('admin', 'event', 'kategori', 'kandidats'));
    }

    // ========== CREATE: Form Tambah ==========
    public function create($eventId, $kategoriId)
    {
        if (!$this->checkWriteAccess()) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        $admin = Auth::guard('admin')->user();
        $event = Event::findOrFail($eventId);
        $kategori = KategoriPemilihan::findOrFail($kategoriId);

        return view('admin.kandidat.create', compact('admin', 'event', 'kategori'));
    }

    // ========== STORE: Simpan Kandidat ==========
    public function store(Request $request, $eventId, $kategoriId)
    {
        if (!$this->checkWriteAccess()) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'nomor_urut' => 'required|integer|min:1',
            'nama_ketua' => 'required|max:255',
            'nim_ketua' => 'required|max:20',
            'foto_ketua' => 'nullable|image|max:2048',
            'nama_wakil' => 'required|max:255',
            'nim_wakil' => 'required|max:20',
            'foto_wakil' => 'nullable|image|max:2048',
            'visi' => 'required',
            'misi' => 'required',
        ]);

        // Check unique nomor urut
        $exists = Kandidat::where('id_kategori', $kategoriId)
            ->where('nomor_urut', $validated['nomor_urut'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['nomor_urut' => 'Nomor urut sudah digunakan'])
                ->withInput();
        }

        $validated['id_kategori'] = $kategoriId;

        // Upload foto
        if ($request->hasFile('foto_ketua')) {
            $validated['foto_ketua'] = $request->file('foto_ketua')->store('kandidat', 'public');
        }
        if ($request->hasFile('foto_wakil')) {
            $validated['foto_wakil'] = $request->file('foto_wakil')->store('kandidat', 'public');
        }

        Kandidat::create($validated);

        return redirect()->route('admin.kandidat.index', [$eventId, $kategoriId])
            ->with('success', 'Kandidat berhasil ditambahkan!');
    }

    // ========== SHOW: Detail Kandidat ==========
    public function show($eventId, $kategoriId, $id)
    {
        $admin = Auth::guard('admin')->user();
        $event = Event::findOrFail($eventId);
        $kategori = KategoriPemilihan::findOrFail($kategoriId);
        $kandidat = Kandidat::findOrFail($id);

        return view('admin.kandidat.show', compact('admin', 'event', 'kategori', 'kandidat'));
    }

    // ========== EDIT: Form Edit ==========
    public function edit($eventId, $kategoriId, $id)
    {
        if (!$this->checkWriteAccess()) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        $admin = Auth::guard('admin')->user();
        $event = Event::findOrFail($eventId);
        $kategori = KategoriPemilihan::findOrFail($kategoriId);
        $kandidat = Kandidat::findOrFail($id);

        return view('admin.kandidat.edit', compact('admin', 'event', 'kategori', 'kandidat'));
    }

    // ========== UPDATE: Update Kandidat ==========
    public function update(Request $request, $eventId, $kategoriId, $id)
    {
        if (!$this->checkWriteAccess()) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        $kandidat = Kandidat::findOrFail($id);

        $validated = $request->validate([
            'nomor_urut' => 'required|integer|min:1',
            'nama_ketua' => 'required|max:255',
            'nim_ketua' => 'required|max:20',
            'foto_ketua' => 'nullable|image|max:2048',
            'nama_wakil' => 'required|max:255',
            'nim_wakil' => 'required|max:20',
            'foto_wakil' => 'nullable|image|max:2048',
            'visi' => 'required',
            'misi' => 'required',
        ]);

        // Check unique nomor urut
        $exists = Kandidat::where('id_kategori', $kategoriId)
            ->where('nomor_urut', $validated['nomor_urut'])
            ->where('id_kandidat', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['nomor_urut' => 'Nomor urut sudah digunakan'])
                ->withInput();
        }

        // Upload foto baru (Observer akan hapus foto lama otomatis)
        if ($request->hasFile('foto_ketua')) {
            $validated['foto_ketua'] = $request->file('foto_ketua')->store('kandidat', 'public');
        }
        if ($request->hasFile('foto_wakil')) {
            $validated['foto_wakil'] = $request->file('foto_wakil')->store('kandidat', 'public');
        }

        $kandidat->update($validated);

        return redirect()->route('admin.kandidat.index', [$eventId, $kategoriId])
            ->with('success', 'Kandidat berhasil diupdate!');
    }

    // ========== DELETE: Soft Delete ==========
    public function destroy($eventId, $kategoriId, $id)
    {
        if (!$this->checkDeleteAccess()) {
            return back()->with('error', 'Hanya Super Admin yang dapat menghapus!');
        }

        $kandidat = Kandidat::findOrFail($id);
        $kandidat->delete();

        return redirect()->route('admin.kandidat.index', [$eventId, $kategoriId])
            ->with('success', 'Kandidat berhasil dihapus!');
    }

    // ========== TRASH: Lihat Kandidat Terhapus ==========
    public function trash()
    {
        if (!$this->checkDeleteAccess()) {
            return redirect()->route('admin.kandidat.selectEvent')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $admin = Auth::guard('admin')->user();

        // PERBAIKAN: Load relasi yang sudah di-soft delete juga
        $kandidats = Kandidat::onlyTrashed()
            ->with([
                'kategoriPemilihan' => function ($query) {
                    $query->withTrashed(); // Load kategori yang dihapus juga
                },
                'kategoriPemilihan.event' => function ($query) {
                    $query->withTrashed(); // Load event yang dihapus juga
                }
            ])
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('admin.kandidat.trash', compact('admin', 'kandidats'));
    }

    // ========== RESTORE: Kembalikan Kandidat ==========
    public function restore($id)
    {
        if (!$this->checkDeleteAccess()) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        $kandidat = Kandidat::onlyTrashed()->findOrFail($id);
        $kandidat->restore();

        return redirect()->route('admin.kandidat.trash')
            ->with('success', 'Kandidat berhasil dipulihkan!');
    }

    // ========== FORCE DELETE: Hapus Permanen ==========
    public function forceDelete($id)
    {
        if (!$this->checkDeleteAccess()) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        $kandidat = Kandidat::onlyTrashed()->findOrFail($id);

        // Foto akan otomatis terhapus oleh Observer
        $kandidat->forceDelete();

        return redirect()->route('admin.kandidat.trash')
            ->with('success', 'Kandidat dan foto berhasil dihapus permanen!');
    }
}
