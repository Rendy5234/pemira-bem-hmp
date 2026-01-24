<?php

namespace App\Observers;

use App\Models\Kandidat;
use Illuminate\Support\Facades\Storage;

class KandidatObserver
{
    /**
     * Handle the Kandidat "updating" event.
     * Hapus foto lama saat update dengan foto baru
     */
    public function updating(Kandidat $kandidat): void
    {
        // Cek apakah foto ketua berubah
        if ($kandidat->isDirty('foto_ketua')) {
            $oldFoto = $kandidat->getOriginal('foto_ketua');
            if ($oldFoto && Storage::disk('public')->exists($oldFoto)) {
                Storage::disk('public')->delete($oldFoto);
            }
        }
        
        // Cek apakah foto wakil berubah
        if ($kandidat->isDirty('foto_wakil')) {
            $oldFoto = $kandidat->getOriginal('foto_wakil');
            if ($oldFoto && Storage::disk('public')->exists($oldFoto)) {
                Storage::disk('public')->delete($oldFoto);
            }
        }
    }

    /**
     * Handle the Kandidat "forceDeleted" event.
     * Hapus foto saat data dihapus permanen
     */
    public function forceDeleted(Kandidat $kandidat): void
    {
        // Hapus foto ketua
        if ($kandidat->foto_ketua && Storage::disk('public')->exists($kandidat->foto_ketua)) {
            Storage::disk('public')->delete($kandidat->foto_ketua);
        }
        
        // Hapus foto wakil
        if ($kandidat->foto_wakil && Storage::disk('public')->exists($kandidat->foto_wakil)) {
            Storage::disk('public')->delete($kandidat->foto_wakil);
        }
    }
}