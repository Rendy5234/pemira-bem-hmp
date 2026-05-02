<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\KategoriPemilihan;
use App\Models\Kandidat;
use App\Models\Pemilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    private function getAdmin()
    {
        return Auth::guard('admin')->user();
    }

    // Halaman utama laporan - pilih event dulu
    public function index(Request $request)
    {
        $admin = $this->getAdmin();

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

        return view('admin.laporan.index', compact('admin', 'events', 'periodes'));
    }

    // Laporan detail per event (semua kategori + kandidat + suara)
    public function detail(Request $request, $eventId)
    {
        $admin = $this->getAdmin();
        $event = Event::with('kategoriPemilihan')->findOrFail($eventId);

        // Filter kategori (opsional)
        $kategoriQuery = KategoriPemilihan::where('id_event', $eventId);
        if ($request->filled('jenis')) {
            $kategoriQuery->where('jenis', $request->jenis);
        }
        $kategoris = $kategoriQuery->get();

        // Ambil data rekapitulasi per kategori dan kandidat
        $rekapData = [];
        foreach ($kategoris as $kategori) {
            // Total suara di kategori ini
            $totalSuara = Pemilihan::where('id_kategori', $kategori->id_kategori)->count();

            // Kandidat dengan jumlah suara masing-masing
            $kandidats = Kandidat::where('id_kategori', $kategori->id_kategori)
                ->orderBy('nomor_urut')
                ->get()
                ->map(function ($kandidat) use ($totalSuara) {
                    $jumlahSuara = Pemilihan::where('id_kandidat', $kandidat->id_kandidat)->count();
                    $persentase = $totalSuara > 0 ? round(($jumlahSuara / $totalSuara) * 100, 1) : 0;
                    return [
                        'kandidat'     => $kandidat,
                        'jumlah_suara' => $jumlahSuara,
                        'persentase'   => $persentase,
                    ];
                });

            // Filter berdasarkan fakultas/prodi jika ada
            $pemilihanQuery = Pemilihan::where('id_kategori', $kategori->id_kategori)
                ->with(['user', 'kandidat']);

            if ($request->filled('fakultas')) {
                $pemilihanQuery->whereHas('user', function ($q) use ($request) {
                    $q->where('fakultas', $request->fakultas);
                });
            }
            if ($request->filled('prodi')) {
                $pemilihanQuery->whereHas('user', function ($q) use ($request) {
                    $q->where('prodi', $request->prodi);
                });
            }
            if ($request->filled('tanggal_mulai')) {
                $pemilihanQuery->whereDate('waktu_pemilihan', '>=', $request->tanggal_mulai);
            }
            if ($request->filled('tanggal_selesai')) {
                $pemilihanQuery->whereDate('waktu_pemilihan', '<=', $request->tanggal_selesai);
            }

            $totalSuaraFiltered = $pemilihanQuery->count();

            // Recalculate dengan filter
            $kandidatsFiltered = Kandidat::where('id_kategori', $kategori->id_kategori)
                ->orderBy('nomor_urut')
                ->get()
                ->map(function ($kandidat) use ($totalSuaraFiltered, $request) {
                    $q = Pemilihan::where('id_kandidat', $kandidat->id_kandidat);
                    if ($request->filled('fakultas')) {
                        $q->whereHas('user', fn($u) => $u->where('fakultas', $request->fakultas));
                    }
                    if ($request->filled('prodi')) {
                        $q->whereHas('user', fn($u) => $u->where('prodi', $request->prodi));
                    }
                    if ($request->filled('tanggal_mulai')) {
                        $q->whereDate('waktu_pemilihan', '>=', $request->tanggal_mulai);
                    }
                    if ($request->filled('tanggal_selesai')) {
                        $q->whereDate('waktu_pemilihan', '<=', $request->tanggal_selesai);
                    }
                    $jumlahSuara = $q->count();
                    $persentase = $totalSuaraFiltered > 0 ? round(($jumlahSuara / $totalSuaraFiltered) * 100, 1) : 0;
                    return [
                        'kandidat'     => $kandidat,
                        'jumlah_suara' => $jumlahSuara,
                        'persentase'   => $persentase,
                    ];
                });

            $rekapData[] = [
                'kategori'     => $kategori,
                'total_suara'  => $totalSuaraFiltered,
                'kandidats'    => $kandidatsFiltered,
            ];
        }

        // Daftar fakultas & prodi unik dari pemilihan di event ini
        $fakultasList = Pemilihan::where('id_event', $eventId)
            ->with('user')
            ->get()
            ->pluck('user.fakultas')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $prodiList = Pemilihan::where('id_event', $eventId)
            ->with('user')
            ->get()
            ->pluck('user.prodi')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Total partisipasi
        $totalPemilih = Pemilihan::where('id_event', $eventId)
            ->distinct('nim')
            ->count('nim');

        return view('admin.laporan.detail', compact(
            'admin', 'event', 'kategoris', 'rekapData',
            'fakultasList', 'prodiList', 'totalPemilih'
        ));
    }

    // Download laporan PDF (menggunakan view yang di-print)
    public function downloadPdf($eventId)
    {
        $admin = $this->getAdmin();
        $event = Event::with('kategoriPemilihan')->findOrFail($eventId);

        $kategoris = KategoriPemilihan::where('id_event', $eventId)->get();

        $rekapData = [];
        foreach ($kategoris as $kategori) {
            $totalSuara = Pemilihan::where('id_kategori', $kategori->id_kategori)->count();
            $kandidats = Kandidat::where('id_kategori', $kategori->id_kategori)
                ->orderBy('nomor_urut')
                ->get()
                ->map(function ($kandidat) use ($totalSuara) {
                    $jumlahSuara = Pemilihan::where('id_kandidat', $kandidat->id_kandidat)->count();
                    $persentase = $totalSuara > 0 ? round(($jumlahSuara / $totalSuara) * 100, 1) : 0;
                    return [
                        'kandidat'     => $kandidat,
                        'jumlah_suara' => $jumlahSuara,
                        'persentase'   => $persentase,
                    ];
                });

            $rekapData[] = [
                'kategori'    => $kategori,
                'total_suara' => $totalSuara,
                'kandidats'   => $kandidats,
            ];
        }

        $totalPemilih = Pemilihan::where('id_event', $eventId)
            ->distinct('nim')
            ->count('nim');

        return view('admin.laporan.print', compact('admin', 'event', 'rekapData', 'totalPemilih'));
    }
}